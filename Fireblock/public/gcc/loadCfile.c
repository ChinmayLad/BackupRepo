#define F_CPU 14745600
#include <avr/io.h>
#include <avr/interrupt.h>
#include <util/delay.h>
#include "firebird.h"



int main() {

	init_devices();
	while(1){
		forward_mm(50);
		back_mm(50);
		left_degrees(90);
		right_degrees(90);
	}

 return 0;
}