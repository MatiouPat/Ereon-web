export interface Personage {
  "@id"?: string;
  id?: number;
  name?: string;
  race?: string;
  alignment?: string;
  class?: string;
  inventory?: string;
  biography?: string;
  imageFile?: File;
  imageName?: string;
  user?: any;
  world?: any;
  numberOfAttributes?: any;
  numberOfSkills?: any;
  numberOfPoints?: any;
}
