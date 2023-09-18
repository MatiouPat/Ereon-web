import axios from "axios";
import { Token } from "../entity/token";

export class TokenRepository
{

    public async createToken(token: Token): Promise<void>
    {
        axios({
            method: 'POST',
            url: '/api/tokens',
            data: token,
            headers: {
                'Content-Type': 'application/json'
            }
        });
    }

    public async deleteToken(tokenId: number): Promise<void>
    {
        axios({
            method: 'DELETE',
            url: '/api/tokens/' + tokenId
        });
    }

    public async updateTokenPartially(token: Token): Promise<void>
    {
        axios({
            method: 'PATCH',
            url: '/api/tokens/' + token.id,
            data: token,
            headers: {
                'Content-Type': 'application/merge-patch+json'
            }
        });
    }

}