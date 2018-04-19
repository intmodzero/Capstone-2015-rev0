#include <msp430.h>

#include "pins.h"

void pins_init() {
	//Leds
	set_bit(DIR(LED_R_PORT),LED_R);
	set_bit(OUT(LED_R_PORT),LED_R);
	
	//UART
	set_bit(DIR(UART_PORT),UART_TX);
	set_bit(OUT(UART_PORT),UART_TX);

	set_bit(SEL1(UART_PORT),UART_TX);
	set_bit(SEL1(UART_PORT),UART_RX);

	clear_bit(DIR(UART_PORT),UART_RX);
	
	//TEMP SPI
	set_bit(DIR(TEMP_SPIIO_PORT),TEMP_MOSI);
	clear_bit(SEL0(TEMP_SPIIO_PORT),TEMP_MOSI);
	set_bit(SEL1(TEMP_SPIIO_PORT),TEMP_MOSI);
	
	clear_bit(DIR(TEMP_SPIIO_PORT),TEMP_MISO);
	clear_bit(SEL0(TEMP_SPIIO_PORT),TEMP_MISO);
	set_bit(SEL1(TEMP_SPIIO_PORT),TEMP_MISO);
	
	set_bit(DIR(TEMP_SCK_PORT),TEMP_SCK);
	clear_bit(SEL0(TEMP_SCK_PORT),TEMP_SCK);
	set_bit(SEL1(TEMP_SCK_PORT),TEMP_SCK);
	
	set_bit(DIR(TEMP_CS_PORT),TEMP_L_CS);
	set_bit(OUT(TEMP_CS_PORT),TEMP_L_CS);

	set_bit(DIR(TEMP_CS_PORT),TEMP_R_CS);
	set_bit(OUT(TEMP_CS_PORT),TEMP_R_CS);

	// ACC SPI

	set_bit(DIR(ACC_CS_PORT),ACC_CS);
	set_bit(OUT(ACC_CS_PORT),ACC_CS);

	// EC
	/*
	set_bit(REN(EC_PORT),EC_L);
	set_bit(REN(EC_PORT),EC_R);
	set_bit(OUT(EC_PORT),EC_L);
	set_bit(OUT(EC_PORT),EC_R);
	*/
}
