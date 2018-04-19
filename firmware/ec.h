/* ec.h
 * 
 * 
 * 
 * 
 */
volatile unsigned int adc_result;

void ec_init(unsigned char sensor) {
	ADC12CTL0 &= ~ADC12ENC;                   // Disable ADC12
  ADC12CTL0 = ADC12SHT0_8 + ADC12ON;        // Set sample time
  ADC12CTL1 = ADC12SHP;                     // Enable sample timer
	ADC12CTL2 |= ADC12RES1;
  //ADC12CTL3 = ADC12TCMAP;                   // Enable internal temperature sensor
  ADC12MCTL0 = ADC12VRSEL_0 + (sensor ? ADC12INCH_0 : ADC12INCH_1); // ADC input ch A30 => temp sense
  ADC12IER0 |= ADC12IE0;                    // ADC_IFG upon conv result-ADCMEMO

  ADC12CTL0 |= ADC12ENC;
}

unsigned int ec_read(unsigned char sensor) {
	ec_init(sensor);
	
	ADC12CTL0 |= ADC12SC;                   // Sampling and conversion start
	__bis_SR_register(LPM0_bits + GIE);     // LPM4 with interrupts enabled
	
	return adc_result;
}

#if defined(__TI_COMPILER_VERSION__) || defined(__IAR_SYSTEMS_ICC__)
#pragma vector=ADC12_VECTOR
__interrupt void ADC12ISR (void)
#elif defined(__GNUC__)
void __attribute__ ((interrupt(ADC12_VECTOR))) ADC12ISR (void)
#else
#error Compiler not supported!
#endif
{
  switch(ADC12IV)
  {
    case ADC12IV_ADC12IFG0:                 // Vector 12:  ADC12MEM0 Interrupt
			adc_result = ADC12MEM0;
      __bic_SR_register_on_exit(LPM4_bits); // Exit active CPU
      break;
    case ADC12IV_ADC12IFG1:                 // Vector 12:  ADC12MEM0 Interrupt
      __bic_SR_register_on_exit(LPM4_bits); // Exit active CPU
			break;
    default: 
      __bic_SR_register_on_exit(LPM4_bits); // Exit active CPU
			break;
  }
}
