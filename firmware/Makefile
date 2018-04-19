# Makefile created by gnomad

PROG = main
DEVICE = msp430fr5959
CC = msp430-elf-gcc
CXX = msp430-elf-g++
OBJDUMP = msp430-elf-objdump
SIZE = msp430-elf-size
MSPDEBUG = mspdebug 
INCLUDE_DIR = /home/michael/ti/gcc/include
CFLAGS = -I $(INCLUDE_DIR) -L $(INCLUDE_DIR) -T $(DEVICE).ld -O2 -std=gnu99 -Wall -mmcu=$(DEVICE)
FET = rf2500 
GDB = msp430-gdb
GDBTUI = $(GDB)tui

OBJS=$(PROG).o pins.o spi.o 

all: $(PROG).elf  $(PROG).lst
	$(SIZE) $(PROG).elf

.PHONY: all

$(PROG).elf: $(OBJS)
	$(CC) $(CFLAGS) $(OBJS) -o $(PROG).elf

%.o: %.cpp
	$(CC) $(CFLAGS) -c $<

%.o: %.S
	$(CC) $(CFLAGS) -c -x assembler-with-cpp $<

%.lst: %.elf
	$(OBJDUMP) -DS $< >$@

clean:
	rm -fr $(PROG).elf $(PROG).lst $(OBJS)

install: $(PROG).elf
	$(MSPDEBUG) $(FET) "prog $(PROG).elf"

mspdebug: $(PROG).elf
	$(MSPDEBUG) $(FET)

debug: $(PROG).elf
	$(MSPDEBUG) $(FET) gdb

gdb: $(PROG).elf
	$(GDB) $(PROG).elf
 
tui: $(PROG).elf
	$(GDBTUI) $(PROG).elf
 
ddd: $(PROG).elf
	ddd --debugger $(GDB) $(PROG).elf
