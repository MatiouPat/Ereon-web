export interface MusicPlayer {
  "@id"?: string;
  id: number;
  isPlaying?: boolean;
  isLooping?: boolean;
  currentMusic?: any;
  currentTimePlay?: number;
}
