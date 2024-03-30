import { Image } from "../entity/image";
import { ImageRepository } from "../repository/imageRepository";

export class ImageService
{
    private imageRepository: ImageRepository = new ImageRepository();
    
     /**
     * Call the image service if an image is uploaded via the form in the character component.
     * @param image 
     */
     public async uploadImage(image: Image): Promise<Image>
     {
         if(!image.imageFile) {
             return image;
         }
 
         if(image.id) {
             return await this.imageRepository.updateImagePartially(image);
         }else {
             return await this.imageRepository.createImage(image);
         }
     }
}