#define FRAME_DELIM 0x7e

struct Packet {
	unsigned char id;
	unsigned char status;
	unsigned char type;
	unsigned char dummy;
	unsigned int temp_l;
	unsigned int temp_r;
	unsigned int ec_l;
	unsigned int ec_r;
	signed int acc_x;
	signed int acc_y;
	signed int acc_z;
};

void send_packet(Packet packet) {
	unsigned char buffer[sizeof(packet)];
	memcpy(buffer,&packet,sizeof(packet));
	for(int i=0; i<sizeof(buffer); i++) {
		//uart.xmit(buffer[i]);
	}
	//uart.xmit(FRAME_DELIM);
	
	printf("id=%u status=%u type=%u\n",packet.id,packet.status,packet.type);
	printf("temp_l=%u temp_r=%u\n",packet.temp_l,packet.temp_r);
}
