import axios from "axios";
import { Spell } from "../entity/spell";

export class SpellRepository
{

    public async findSpellByWorld(worldId: number): Promise<Spell[]>
    {
        return axios({
            method: 'GET',
            url: '/api/spells?world.id=' + worldId
        })
        .then(res => {
            return res.data['hydra:member'];
        })
    }

    public async createSpell(spell: Spell): Promise<Spell>
    {
        return axios({
            method: 'POST',
            url: '/api/spells',
            data: spell,
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(res => {
            return res.data;
        })
    }

}