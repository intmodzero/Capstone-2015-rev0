#pragma once

void init_8MHz() {
	// Startup clock system with max DCO setting ~8MHz
	CSCTL0_H = CSKEY >> 8;                    // Unlock clock registers
	CSCTL1 = DCOFSEL_3 | DCORSEL;             // Set DCO to 8MHz
	CSCTL2 = SELA__VLOCLK | SELS__DCOCLK | SELM__DCOCLK;
	CSCTL3 = DIVA__1 | DIVS__1 | DIVM__1;     // set all dividers
	CSCTL0_H = 0;                             // Lock CS registers
}

#define seconds *F_CPU
#define ms *(F_CPU/1000)
#define delay __delay_cycles
