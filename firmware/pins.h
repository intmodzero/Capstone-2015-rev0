#pragma once

#include "bitop.h"
#include "config.h"
#include "portmacros.h"

//UCA0
#define UART_PORT 2
#define UART_TX 0
#define UART_RX 1

#define UART_TS_PORT 4
#define UART_CTS 1
#define UART_RTS 2

#define LED_R_PORT 1
#define LED_R 5

#define SW_L_PORT 4
#define SW_L 5

#define SW_R_PORT 1
#define SW_R 1

// TSYS01 - Left and Right Fingers
#define TEMP_SPIIO_PORT 1
#define TEMP_MISO 7
#define TEMP_MOSI 6

#define TEMP_SCK_PORT 2
#define TEMP_SCK 2

#define TEMP_CS_PORT 3
#define TEMP_L_CS 1
#define TEMP_R_CS 0

#define ACC_SPIIO_PORT 1
#define ACC_MISO 7
#define ACC_MOSI 6

#define ACC_SCK_PORT 2
#define ACC_SCK 2

#define ACC_CS_PORT 3
#define ACC_CS 2

#define acc_spi_send_receive spi_UCB0_send_receive
#define acc_spi_init spi_UCB0_init

#define EC_PORT 1
#define EC_L 1
#define EC_R 0

void pins_init();
