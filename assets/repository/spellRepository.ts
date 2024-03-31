import { Spell } from "../entity/spell";
import { AbstractRepository } from "../utils/abstractRepository";

export class SpellRepository extends AbstractRepository
{
    public async findSpellByWorld(worldId: number): Promise<Spell[]>
    {
        return this.createQueryBuilder('GET', '/api/spells?world.id=' + worldId)
            .getResult()
    }

    public async createSpell(spell: Spell): Promise<Spell>
    {
        return this.createQueryBuilder('POST', '/api/spells')
            .addData(spell)
            .getOneOrNullResult()
    }

}