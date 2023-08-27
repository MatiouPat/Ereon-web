export interface Connection {
  "@id"?: string;
  id: number;
  isGameMaster?: boolean;
  lastConnectionAt?: Date;
  world?: any;
  user?: any;
  currentMap?: any;
}
