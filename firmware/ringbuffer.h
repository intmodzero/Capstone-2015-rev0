#ifndef RINGBUFFER_H_
#define RINGBUFFER_H_

#ifdef __MSPGCC__
#define _enable_interrupts() __bis_status_register(GIE)
#define _disable_interrupts() __bic_status_register(GIE)
#endif

/**
 * ringbuffer - a template based interrupt safe circular buffer structure with functions
 */

template<typename T, int MAX_ITEMS>
struct ringbuffer {
	volatile int head;
	volatile int tail;
	volatile T buffer[MAX_ITEMS];

	/**
	* empty() - checks the buffer for data
	*
	* returns true if empty, false if there is data
	*/
	inline bool empty() {
		bool isEmpty;
		
		_disable_interrupts(); // prevent inconsistent reads
		isEmpty = (head == tail);
		_enable_interrupts();
		
		return isEmpty;
	}
	
	/**
	* push_back() - append a byte to the buffer is possible
	*               assumed to be called from the recv interrupt
	*/
	inline void push_back(T c) {
		int i = (unsigned int) (head + 1) % MAX_ITEMS;
		if (i != tail) {
			buffer[head] = c;
			head = i;
		}
	}
	
	/**
	* pop_front() - remove a value from front of ring buffer
	*/
	inline T pop_front() {
		T c = -1;
		
		_disable_interrupts(); // disable interrupts to protect head and tail values
		// This prevents the RX_ISR from modifying them
		// while we are trying to read and modify
		
		// if the head isn't ahead of the tail, we don't have any characters
		if (head != tail) {
			c = (T) buffer[tail];
			tail = (unsigned int) (tail + 1) % MAX_ITEMS;
		}
		
		_enable_interrupts(); // ok .. let everyone at them
		
		return c;
	}
};

//Examples
//typedef ringbuffer<uint8_t, 16> ringbuffer_ui8_16; // ringbuffer, max of 16 uint8_t values
//typedef ringbuffer<uint8_t, 32> Ringbuffer_uint8_32; // ringbuffer, max of 32 uint8_t values

#endif /* RINGBUFFER_H_ */
