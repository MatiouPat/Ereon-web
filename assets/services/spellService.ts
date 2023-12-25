import { Spell } from "../entity/spell";
import { SpellRepository } from "../repository/spellRepository";

export class SpellService
{

    private spellRepository: SpellRepository = new SpellRepository();

    public async findSpellByWorld(worldId: number)
    {
        return this.spellRepository.findSpellByWorld(worldId);
    }

    public async createSpell(spell: Spell): Promise<Spell>
    {
        return this.spellRepository.createSpell(spell);
    }

}