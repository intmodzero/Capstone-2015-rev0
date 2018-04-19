/*
 * The Magic Mouse - Prototype
 * 
 * Author: Michael Lawson
 * Last Revision: 23/10/2014
 *
 */

#include <msp430.h>
#include <string.h>

#ifdef __cplusplus
extern "C" {
#endif

#include <cstdio>

#include "config.h"
#include "clock.h"
#include "pins.h"
#include "spi.h"

#ifdef __cplusplus
}
#endif

#include "ringbuffer.h"
typedef  ringbuffer<char, 64> uart_buffer_t;

uart_buffer_t uart_buffer = { 0, 0, { 0 } };

#include "usci_uart.h"
UCA0<uart_buffer_t> uart = { uart_buffer };

// give printf a source to output to (uart)
int putchar(int c) {
	uart.xmit((char)c);
	return 1;
}
#include "temp.h"
#include "mpu6000.h"
#include "ec.h"
//#include "protocol.h"


#define LEFT 1
#define RIGHT 0

void get_bt_address() {
	uart.xmit(BT_ID);	
	uart.xmit('\n');
}

void chip_init(void) {
	WDTCTL = WDTPW + WDTHOLD; //Stop WDT

	pins_init();
	set_bit(OUT(LED_R_PORT),LED_R);
	//Enable pins after powerup according to 8.3.1(page 310) from SLAU367B
	clear_bits(PM5CTL0,LOCKLPM5);

	// Configure one FRAM waitstate as required by the device datasheet for MCLK
	// operation beyond 8MHz _before_ configuring the clock system.
	FRCTL0 = FWPW | NACCESS_1;

	//Enable clock
	init_8MHz();

	//Debug UART
	uart.init(115200);

	// Temp SPI

	spi_UCB0_init(UCCKPL*0+UCCKPH*1);     

	//Enable interrupts
	__bis_SR_register(GIE);
	
	temp_init(LEFT);
	temp_init(RIGHT);
	
	acc_init();

	get_bt_address();
}

unsigned char started = 0;

int main(void) {
	unsigned int tempL=0;
	unsigned int tempR=0;
	unsigned int ecL=0;
	unsigned int ecR=0;
	struct acc_data_struct acc_data;
	char buf[100]; 

	chip_init();
	
	while(1) {
		if(command_present) {
			if(strncmp("bt",command,2)==0) {
				get_bt_address();	
			}
			else if(strncmp("start",command,5)==0) {
				started = 1;		
			}
			else if(strncmp("stop",command,4)==0) {
				started = 0;		
			}
			else if(strncmp("cal",command,3)==0) {
				temp_cal(LEFT);
				temp_cal(RIGHT);
			}
			else {
				uart.xmit("unknown command\n");	
			}
			
			command_present = 0;
		}

		if(started) {
			tempL = temp_read(LEFT);
			tempR = temp_read(RIGHT);
			acc_read(&acc_data);
			ecL = ec_read(LEFT);	
			ecR = ec_read(RIGHT);	

			sprintf(buf,"{\"t_l\": %u, \"t_r\": %u, \"acc_x\": %d, \"acc_y\": %d, \"acc_z\": %d, \"ec_l\": %u, \"ec_r\": %u}\n",tempL,tempR,acc_data.x,acc_data.y,acc_data.z,ecL,ecR);
			uart.xmit(buf);
			
			delay(100 ms);
		}
	}

	return 0;
}
