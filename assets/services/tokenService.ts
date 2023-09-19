import { Token } from "../entity/token";
import { User } from "../entity/user";
import { TokenRepository } from "../repository/tokenRepository";

export class TokenService
{
    private tokenRepository: TokenRepository = new TokenRepository();

    public async createToken(layer: number, mapId: number, assetId: number): Promise<void>
    {
        let token:Token = {
            "width": 64,
            "height": 64,
            "topPosition": 0,
            "leftPosition": 0,
            "zIndex": 0,
            "layer": layer,
            "map": "/api/maps/" + mapId,
            "asset": "/api/assets/" + assetId
        };
        this.tokenRepository.createToken(token);
    }

    public async deleteToken(tokenId: number): Promise<void>
    {
        this.tokenRepository.deleteToken(tokenId);
    }

    public async updateTokenPartially(token: Token): Promise<void>
    {
        let req: Token = JSON.parse(JSON.stringify(token));
        req.asset = undefined;
        req.users.forEach((user: User, key: number) => {
            req.users[key] = '/api/users/' + user.id
        })
        this.tokenRepository.updateTokenPartially(req);
    }

}