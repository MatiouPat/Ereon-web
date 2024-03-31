import { Token } from "../entity/token";
import { AbstractRepository } from "../utils/abstractRepository";

export class TokenRepository extends AbstractRepository
{
    public async createToken(token: Token): Promise<void>
    {
        return this.createQueryBuilder('POST', '/api/tokens')
            .addData(token)
            .getOneOrNullResult()
    }

    public async deleteToken(tokenId: number): Promise<void>
    {
        return this.createQueryBuilder('DELETE', '/api/tokens/' + tokenId)
            .getOneOrNullResult()
    }

    public async updateTokenPartially(token: Token): Promise<void>
    {
        return this.createQueryBuilder('PATCH', '/api/tokens/' + token.id)
            .addData(token)
            .getOneOrNullResult()
    }

}