import axios from "axios";
import { Image } from "../entity/image";
import { AbstractRepository } from "../utils/abstractRepository";

export class ImageRepository extends AbstractRepository
{
    public async createImage(image: Image): Promise<Image>
    {
        let formData = new FormData();
        formData.append("imageFile", image.imageFile);
        return this.createQueryBuilder('POST', '/api/images')
            .setIsMultipartMethod(true)
            .addData(formData)
            .getOneOrNullResult()
    }

    public async updateImagePartially(image: Image): Promise<Image>
    {
        let formData = new FormData();
        formData.append("imageFile", image.imageFile);
        return this.createQueryBuilder('POST', '/api/images' + image.id)
            .setIsMultipartMethod(true)
            .addData(formData)
            .getOneOrNullResult()
    }

}