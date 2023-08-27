import { Music } from "./music";

export interface MusicPlayer {
  "@id"?: string;
  id: number;
  isPlaying?: boolean;
  isLooping?: boolean;
  currentMusic?: Music;
  currentTimePlay?: number;
}
