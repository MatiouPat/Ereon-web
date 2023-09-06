import { NumberOfAttribute } from "./numberofattribute";
import { User } from "./user";
import { World } from "./world";

export interface Personage {
  "@id"?: string;
  id: number;
  name?: string;
  race?: string;
  alignment?: string;
  class?: string;
  inventory?: string;
  user?: User;
  world?: World;
  numberOfAttributes?: NumberOfAttribute[];
  numberOfSkills?: any;
  numberOfPoints?: any;
}
