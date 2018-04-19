#!/usr/bin/env python2
"""
magic_mouse.py

The Magic Mouse
McMaster University
Computer Science Capstone
2014/2015

Michael Lawson
Victor Tang
"""

from PySide import QtCore,QtGui
from mysql import *
from rn42 import *
from convert import k_to_temp_function
import bcrypt
import requests
import json
import threading
from collections import deque
import time

status = {"unknown":"<b>[unknown]</b>","connected":"<b><font color='green'>[connected]</font></b>","disconnected":"<b><font color='red'>[disconnected]</font></b>","connecting":"<b>[connecting]</b>","failed":"<b><font color='red'>[failed]</font></b>","logging":"<b><font color='green'>[logging]</font></b>"}
reason = {112: "Can't find device.", 113: "Bluetooth turned off."}

glossy_style = """
 QPushButton {
 color: #fff;
 border: 1px solid black;
 border-radius: 3px;
 padding: 1px;
 background: qlineargradient(
   x1:0, y1:0, x2:0, y2:1,
   stop:0 #bec1d2,
   stop: 0.4 #717990,
   stop: 0.5 #5c637d
   stop:1 #68778e
 );
}

 QPushButton:pressed {
background: qlineargradient(
  x1:0, y1:0, x2:0, y2:1,
  stop:0 #68778e,
  stop: 0.4 #5c637d
  stop: 0.5 #717990,
  stop:1 #bec1d2
 );
 color: black;
}

 QLineEdit {
 background: qlineargradient(
  x1:0, y1:0, x2:0, y2:1,
  stop:0 gray,
  stop: 0.2 white
  stop:1 white
 );
 border-radius: 1px;
 border: 1px solid black;
 min-height: 24px;
 color: black;
}

 QCheckBox {
     color:  white;
 }

 QCheckBox::indicator {
     position: absolute;
     height: 27px;
     width: 64px;
 }

 QCheckBox::indicator:checked {
     image: url(:/checkbox_on.png);
 }

 QCheckBox::indicator:unchecked {
      image: url(:/checkbox_off.png);
 }
 MagicMouseConfig {
     background: #fff;
     }
"""

class Logger(object):
    logger_url = "http://mousetrap.cas.mcmaster.ca/magicmouse/insertReadingsRemote.php"
    def __init__(self, readings):
        self.readings = readings
        payload = {"data": json.dumps(readings)}
        response = requests.post(self.logger_url,data=payload)

class LoginWindow(QtGui.QDialog):
    pw_url = "http://mousetrap.cas.mcmaster.ca/magicmouse/checkLoginRemote.php"
    def __init__(self,parent):
        super(LoginWindow,self).__init__()
        self.parent = parent
        self.createLayout()
        self.createWidgets()
        self.setWindowTitle("Log In")
    def createLayout(self):
        self.layout = QtGui.QVBoxLayout()
        self.login_layout = QtGui.QGridLayout()
        self.setLayout(self.layout)
    def createWidgets(self):
        self.login_box = QtGui.QLineEdit()
        self.login_label = QtGui.QLabel("Username")
        self.pass_box = QtGui.QLineEdit()
        self.pass_box.setEchoMode(QtGui.QLineEdit.Password)
        self.pass_label = QtGui.QLabel("Password")
        self.login_layout.addWidget(self.login_label,0,0)
        self.login_layout.addWidget(self.login_box,0,1)
        self.login_layout.addWidget(self.pass_label,1,0)
        self.login_layout.addWidget(self.pass_box,1,1)
        self.layout.addLayout(self.login_layout)

        self.login_button = QtGui.QPushButton("Log In")
        self.login_button.clicked.connect(self.check_password)
        self.login_layout.addWidget(self.login_button,2,0)

        self.cancel_button = QtGui.QPushButton("Cancel")
        self.cancel_button.clicked.connect(self.reject)
        self.login_layout.addWidget(self.cancel_button,2,1)
    def check_password(self):
        password = self.pass_box.text()
        username = self.login_box.text()
        payload = {"username": username}
        result = requests.post(self.pw_url,data=payload)
        user,pw_hash = result.text.split(",")
        print user
        if pw_hash:
            pw_valid = bcrypt.hashpw(str(password),str(pw_hash))
        else:
            pw_valid = False
        if pw_valid:
            self.parent.logged_in = True
            self.parent.username = username
            self.parent.user_id = user
            self.accept()
        else:
            self.reject()

class StatusMessage(QtGui.QWidget):
    def __init__(self,label,default_message):
        super(StatusMessage,self).__init__()
        self.setStyleSheet("QLabel { font-weight: bold; } QHBoxLayout{ background: #ccc; border-radius: 5px; ");
        self.layout = QtGui.QHBoxLayout()
        self.label = QtGui.QLabel(label)
        self.message = QtGui.QLabel(default_message)
        self.message.setMinimumWidth(300)
        self.layout.addWidget(self.label)
        self.layout.addWidget(self.message)
        self.setLayout(self.layout)
    def update_message(self,message):
        self.message.setText(message)

class MagicMouseConfig(QtGui.QWidget):
    server_address = "http://mousetrap.cas.mcmaster.ca"
    logged_in = False
    username = "<b>[not logged in]</b>"
    device_connected = False
    server_connected = False
    user_id = -1
    def __init__(self,app):
        self.app = app
        super(MagicMouseConfig,self).__init__()
        self.setWindowTitle("The Magic Mouse Driver")
        self.createLayouts()
        self.createWidgets()
        self.setStyleSheet(glossy_style)
        self.tfL = k_to_temp_function(kL)
        self.tfR = k_to_temp_function(kR)
        self.show()
    def createLayouts(self):
        self.layout = QtGui.QVBoxLayout()
        self.setLayout(self.layout)
    def createWidgets(self):
        image = QtGui.QImage("logo_digital.png").scaledToWidth(300)
        self.logo = QtGui.QLabel()
        self.logo.setAlignment(QtCore.Qt.AlignCenter)
        self.logo.setPixmap(QtGui.QPixmap.fromImage(image))

        self.user_status = StatusMessage("Logged in User:",self.username)
        self.device_connection = StatusMessage("Device Connection Status:",status["unknown"])
        self.server_connection = StatusMessage("Server Connection Status:",status["unknown"])
        self.driver_status = StatusMessage("Driver Status:",status["unknown"])

        self.button_layout = QtGui.QHBoxLayout()

        self.connect_button = QtGui.QPushButton("Disconnect")
        self.connect_button.clicked.connect(self.disconnect_device)

        self.login_button = QtGui.QPushButton("Log In")
        self.login_button.clicked.connect(self.login)

        self.layout.addWidget(self.logo)
        self.layout.addWidget(self.user_status)
        self.layout.addWidget(self.device_connection)
        self.layout.addWidget(self.server_connection)
        self.layout.addWidget(self.driver_status)

        self.button_layout.addWidget(self.login_button)
        self.button_layout.addWidget(self.connect_button)

        self.layout.addLayout(self.button_layout)
    def login(self):
        self.login_window = LoginWindow(self)
        self.login_window.exec_()
        if self.logged_in:
            self.user_status.update_message(self.username)
            self.connect_device()
            self.connect_server()
            self.driver_status.update_message(status["logging"])
            self.start_device()
        else:
            pass
    def disconnect_device(self):
        self.logging = False
        self.username = "<b>[not logged in]</b>"
        self.driver_status.update_message(status["disconnected"])
        self.device_connection.update_message(status["disconnected"])
        self.user_status.update_message(self.username)
    def connect_device(self):
        self.device_connection.update_message(status["connecting"])
        self.app.processEvents()
        try:
            self.magic_mouse = RN42('00:06:66:61:1E:CF')
            self.device_connection.update_message(status["connected"])
            self.device_connected = True
        except Exception as e:
            exec("code,message=%s"%e)
            if code in reason:
                error = reason[code]
            else:
                error = "Unknown error. Disconnect USB and reconnect."
            self.device_connection.update_message("%s %s"%(status["failed"],error))
    def connect_server(self):
        self.server_connection.update_message(status["connecting"])
        self.app.processEvents()
        try:
            response = requests.get(self.server_address)
            self.server_connection.update_message(status["connected"])
        except Exception as e:
            self.server_connection.update_message("%s Can't connect to server."%status["failed"])
    def start_device(self):
        self.app.processEvents()
        self.magic_mouse.start_reading()
        self.logging = True
        self.stream_data()
        self.magic_mouse.stop_reading()
        self.magic_mouse.socket.close()
        pass
    def add_meta(self,reading):
        reading["timestamp"] = str(time.time())
        reading["t_l"] = self.tfL(int(reading["t_l"]))
        reading["t_r"] = self.tfR(int(reading["t_r"]))
        reading["id"] = 0
        reading["account_id"] = self.user_id
        return reading
    def stream_data(self):
        readings = []
        while self.logging:
            readings.append(self.add_meta(self.magic_mouse.get_reading()))
            if len(readings) > 100:
                log = Logger(readings[:100])
                readings = readings[100:]
            self.app.processEvents()

if __name__ == "__main__":
    import sys
    app = QtGui.QApplication(sys.argv)
    mw = MagicMouseConfig(app)
    sys.exit(app.exec_())
