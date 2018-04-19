import serial
import numpy

kR=numpy.array([5714,7338,15996,22664,34212])
kL=numpy.array([5714,7338,15996,22650,34189])
coeffs=numpy.array([-2,4,-2,1,-1.5])
scalars=numpy.array([10**(-21),10**(-16),10**(-11),10**(-6),10**(-2)])

"""
CAL@0xa2=5714
CAL@0xa4=7338
CAL@0xa6=15996
CAL@0xa8=22667
CAL@0xaa=34229
"""

def k_to_temp_function(k):
    return numpy.poly1d(k*coeffs*scalars)

if __name__ == "__main__":
    stream_temps()
    tf = k_to_temp_function(kL)

    x = []
    y = []

    for i in xrange(33000,40000):
        real_temp = tf(i)
        try:
            if tf(i) - y[-1] >= 0.1:
                y.append(tf(i))
                x.append(i)
        except IndexError:
            y.append(tf(i))
            x.append(i)


    print y

    import matplotlib.pyplot as plt
    fig = plt.figure()
    plt.plot(x,y,'+')
    plt.show()
