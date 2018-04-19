#include "spi.h"
#include "pins.h"

unsigned char k[5];

void temp_init(unsigned char sensor) {
	clear_bit(OUT(TEMP_CS_PORT),sensor ? TEMP_L_CS : TEMP_R_CS);
	
	spi_UCB0_send_receive(0x1E);

	while(!test_bit(IN(TEMP_SPIIO_PORT),TEMP_MISO));
	
	set_bit(OUT(TEMP_CS_PORT),sensor ? TEMP_L_CS : TEMP_R_CS);
}

void temp_cal(unsigned char sensor) {
	
	const char * title = sensor ? "L" : "R";
	
	char buf[20];
	
	
	//printf("CAL%s=[", title);
	uart.xmit("CAL");
	uart.xmit(title);
	uart.xmit("=[");
	
	for(int i=0xA2; i<0xAB; i+=2) {	
		unsigned char msb=0;
		unsigned char lsb=0;
		
		clear_bit(OUT(TEMP_CS_PORT),sensor ? TEMP_L_CS : TEMP_R_CS);
	
		// send address of coefficient register
		spi_UCB0_send_receive(i);
		
		// receive msb and lsb
		msb = spi_UCB0_send_receive(0x00);
		lsb = spi_UCB0_send_receive(0x00);

		set_bit(OUT(TEMP_CS_PORT),sensor ? TEMP_L_CS : TEMP_R_CS);
		
		unsigned int value = (((unsigned int)msb)<<8) + lsb;
		
		sprintf(buf,"%u,",value);
		uart.xmit(buf);
	}
	uart.xmit("\b]\n");

}

unsigned int temp_read(unsigned char sensor) {
	unsigned long long temp = 0;
	
	clear_bit(OUT(TEMP_CS_PORT),sensor ? TEMP_L_CS : TEMP_R_CS);

	spi_UCB0_send_receive(0x48);
	while(!test_bit(IN(TEMP_SPIIO_PORT),TEMP_MISO));
	
	set_bit(OUT(TEMP_CS_PORT),sensor ? TEMP_L_CS : TEMP_R_CS);
	clear_bit(OUT(TEMP_CS_PORT),sensor ? TEMP_L_CS : TEMP_R_CS);

	temp += ((unsigned long) spi_UCB0_send_receive(0x00))<<16;
	temp += ((unsigned long) spi_UCB0_send_receive(0x00))<<8;
	temp += ((unsigned long) spi_UCB0_send_receive(0x00));

	set_bit(OUT(TEMP_CS_PORT),sensor ? TEMP_L_CS : TEMP_R_CS);
	
	return (unsigned int) temp;
}
