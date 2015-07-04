#include"firebird.h"
#include<util/delay.h>

int main() {

   init_devices();
   while(1){
   
      buzzer_on();
      _delay_ms(0);
      buzzer_off();
      _delay_ms(0);
   }

 return 0;
}