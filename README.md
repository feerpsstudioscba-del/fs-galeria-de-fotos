# FS Galeria de Fotos

Plugin WordPress com widget Elementor para galerias de fotos, com suporte a galeria manual, JetEngine/meta field, layout em mosaico, Masonry, lightbox e marca d'agua visual.

## Recursos

- Widget Elementor sem shortcode.
- Origem das imagens por Galeria Manual do Elementor.
- Origem das imagens por JetEngine / Meta Field.
- Layout Grid com imagem principal e miniaturas.
- Layout Masonry para fotos verticais e horizontais.
- Controle de colunas no Masonry de 3 a 10.
- Ordem das fotos: ASC, DESC ou Random.
- Lightbox com contador de fotos.
- Overlay +N quando houver mais fotos do que as exibidas no mosaico.
- Marca d'agua por imagem fixa ou por campo JetEngine/meta.
- Controles de opacidade, tamanho, largura maxima, rotacao, blend mode e z-index da marca d'agua.
- Marca d'agua apenas como camada visual HTML/CSS, sem alterar arquivos originais.

## Instalacao

1. Envie a pasta `fs-galeria-fotos` para `wp-content/plugins/`.
2. Ative o plugin **FS Galeria Fotos** no painel do WordPress.
3. No Elementor, adicione o widget **FS Galeria de Fotos**.

## Uso Com Galeria Manual

1. Em **Conteudo > Configuracoes da galeria**, selecione `Galeria Manual`.
2. Escolha as imagens no controle **Galeria**.
3. Configure o tipo de galeria como `Grid` ou `Masonry`.

## Uso Com JetEngine

1. Crie um campo Gallery ou Media no JetEngine vinculado ao post type desejado.
2. Salve as fotos no post, pagina ou conteudo correspondente.
3. No widget, selecione `JetEngine / Meta Field`.
4. Informe o nome/chave do campo, por exemplo `galeria` ou `galeria_fotos`.
5. Em templates single do Elementor, configure o preview para um conteudo real ou informe o **Post ID manual**.

O widget tenta detectar o post atual por:

- Post ID manual.
- Objeto atual do JetEngine.
- `get_the_ID()`.
- `get_queried_object_id()`.
- Preview/documento atual do Elementor.

## Marca D'Agua

A marca d'agua pode vir de:

- Imagem padrao selecionada na biblioteca de midia.
- Campo JetEngine/meta field.

A marca d'agua e renderizada como overlay HTML/CSS centralizado sobre a imagem. O plugin nao edita, sobrescreve, compacta, recria ou modifica os arquivos originais das imagens.

## Estrutura

```text
fs-galeria-fotos.php
includes/
  class-plugin.php
  widgets/
    class-fs-imob-gallery-widget.php
assets/
  css/
    fs-imob-gallery.css
  js/
    fs-imob-gallery.js
```

## Requisitos

- WordPress.
- Elementor.
- JetEngine opcional, apenas para uso com campos dinamicos/meta fields.
