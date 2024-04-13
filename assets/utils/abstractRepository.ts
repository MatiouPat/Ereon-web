import { QueryBuilder } from "./queryBuilder";

export class AbstractRepository
{
    protected createQueryBuilder(method: string, url: string): QueryBuilder
    {
        return new QueryBuilder(method, url);
    }

}