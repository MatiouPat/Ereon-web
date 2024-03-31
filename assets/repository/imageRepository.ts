import axios from "axios";
import { Image } from "../entity/image";
import { AbstractRepository } from "../utils/abstractRepository";

export class ImageRepository extends AbstractRepository
{
    public async createImage(image: Image): Promise<Image>
    {
        return this.createQueryBuilder('POST', '/api/images')
            .setIsMultipartMethod(true)
            .addData({
                imageFile: image.imageFile
            })
            .getOneOrNullResult()
    }

    public async updateImagePartially(image: Image): Promise<Image>
    {
        return this.createQueryBuilder('POST', '/api/images' + image.id)
            .setIsMultipartMethod(true)
            .addData({
                imageFile: image.imageFile
            })
            .getOneOrNullResult()
    }

}