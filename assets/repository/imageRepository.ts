import axios from "axios";
import { Image } from "../entity/image";

export class ImageRepository
{

    public async createImage(image: Image): Promise<Image>
    {
        return axios({
            method: 'POST',
            url: '/api/images',
            data: {
                imageFile: image.imageFile
            },
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        })
        .then((res) => {
            return res.data
        })
    }

    public async updateImagePartially(image: Image): Promise<Image>
    {
        return axios({
            method: 'POST',
            url: '/api/images/' + image.id,
            data: {
                imageFile: image.imageFile
            },
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        })
        .then((res) => {
            return res.data
        })
    }

}