precision mediump float;

varying vec2 texCoord;

uniform sampler2D lightTexture;
uniform sampler2D sceneTexture;

void main() {
  vec4 lightColor = texture(lightTexture, texCoord);
  vec4 sceneColor = texture(sceneTexture, texCoord);
  
  float lightIntensity = 1.1 * lightColor.r - 0.33;
  gl_FragColor = vec4(sceneColor.rgb, lightIntensity);
}
