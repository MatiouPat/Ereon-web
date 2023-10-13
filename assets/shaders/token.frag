precision mediump float;

varying vec2 texCoord;

uniform sampler2D tokenTexture;

void main() {

  gl_FragColor = texture(tokenTexture, vec2(texCoord.x,(1.0 - texCoord.y)));
}
