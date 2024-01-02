export interface User {
  "@id"?: string;
  id: number;
  username?: string;
  plainPassword?: string;
  discordIdentifier?: string;
  connections?: any;
  userParameter?: any;
}
