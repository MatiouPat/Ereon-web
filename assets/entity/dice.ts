export interface Dice {
  "@id"?: string;
  id: number;
  computation?: string;
  personage?: any;
  launcher?: any;
  readonly diceNumber?: number;
  readonly brutValue?: number;
  readonly finalValue?: number;
}
