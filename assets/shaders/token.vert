precision mediump float;

attribute vec2 vertex;
attribute vec2 position;

varying vec2 texCoord;

void main() {
  texCoord = (vertex + 1.0) / 2.0;
  
  gl_Position = vec4(vertex + position, 0.0, 1.0);
}
