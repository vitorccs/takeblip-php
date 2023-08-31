<?php

namespace TakeBlip\Test\DataProviders;

use TakeBlip\Builders\TemplateBuilder;

trait WhatsAppTemplates
{
    public static function dataValidTextTemplate(): array
    {
        $builder = new TemplateBuilder();
        $template = $builder
            ->create('user_identity',
                'my_template_name',
                'my_namespace_name')
            ->addVariable('MyVar1')
            ->addVariable('MyVar2')
            ->addReply('QuickReply1')
            ->addReply('QuickReply2')
            ->get();

        return [
            'valid_text_template' => [$template]
        ];
    }

    public static function dataValidDocumentTemplate(): array
    {
        $builder = new TemplateBuilder();
        $template = $builder
            ->create('user_identity',
                'my_template_name',
                'my_namespace_name')
            ->setUrl('https://www.domain.com/file.pdf', 'document', 'Your PDF file')
            ->get();

        return [
            'valid_document_template' => [$template]
        ];
    }

    public static function dataValidImageTemplate(): array
    {
        $builder = new TemplateBuilder();
        $template = $builder
            ->create('user_identity',
                'my_template_name',
                'my_namespace_name')
            ->setUrl('https://www.domain.com/file.png', 'image', 'Your Picture')
            ->get();

        return [
            'valid_image_template' => [$template]
        ];
    }

    public static function dataValidVideoTemplate(): array
    {
        $builder = new TemplateBuilder();
        $template = $builder
            ->create('user_identity',
                'my_template_name',
                'my_namespace_name')
            ->setUrl('https://www.domain.com/file.mp4', 'video', 'Your Video')
            ->get();

        return [
            'valid_video_template' => [$template]
        ];
    }

    public static function dataInvalidTemplate(): array
    {
        $builder = new TemplateBuilder();
        $template = $builder
            ->create('user_identity',
                'my_template_name',
                'my_namespace_name')
            ->setUrl('https://www.domain.com/file.png', 'INVALID', 'Your Picture')
            ->get();

        return [
            'invalid_template' => [$template]
        ];
    }
}
