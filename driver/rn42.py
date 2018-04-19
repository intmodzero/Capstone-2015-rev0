#!/usr/bin/env python2
"""
rn42.py

The Magic Mouse
McMaster University
Computer Science Capstone
2014/2015

Michael Lawson
Victor Tang
"""

from bluetooth import *
import struct
import logging
import mysql
import time
import json

FORMAT = '%(asctime)-15s.%(msecs)d(%(name)s): %(message)s'
logging.basicConfig(format=FORMAT,level=logging.DEBUG,datefmt="%Y-%m-%d %H:%M:%S")

class RN42(object):
    port = 1
    def __init__(self,host):
        self.host = host
        self.socket = BluetoothSocket(RFCOMM)
        self.connect()
    def connect(self):
        logging.debug("Initiating bluetooth connection")
        self.socket.connect((self.host,self.port))
        logging.debug("Connected to [%s]"%self.host)
    def configure(self):
        pass
    def readline(self):
        s = self.socket.recv(1)
        while s[-1] != "\n":
            s += self.socket.recv(1)
        return s[:-1]
    def start_reading(self):
        self.socket.send("start\n")
    def stop_reading(self):
        self.socket.send("stop\n")
    def get_reading(self):
        try:
            reading = json.loads(self.readline())
        except ValueError:
            reading = json.loads(self.readline())
        return reading
