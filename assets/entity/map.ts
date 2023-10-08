export interface Map {
  "@id"?: string;
  id: number;
  name?: string;
  width?: number;
  height?: number;
  hasDynamicLight?: boolean;
  tokens?: any;
  connections?: any;
  lightingWalls?: any;
}
