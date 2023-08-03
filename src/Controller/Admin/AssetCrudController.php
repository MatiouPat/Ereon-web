<?php

namespace App\Controller\Admin;

use App\Entity\Asset;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;

class AssetCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Asset::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            ImageField::new('image')
                ->setBasePath('/uploads/images/asset')
                ->setUploadDir('/public/uploads/images/asset')
                ->setUploadedFileNamePattern('[slug].[extension]'),
            ImageField::new('compressedImage')
                ->setBasePath('/uploads/images/asset')
                ->setUploadDir('/public/uploads/images/asset')
                ->setUploadedFileNamePattern('[slug].[extension]')
                ->onlyOnForms()
        ];
    }
}
