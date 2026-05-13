<?php

namespace FS_Galeria_Fotos\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class FS_Imob_Gallery_Widget extends Widget_Base {
	public function get_name() {
		return 'fs_imob_gallery';
	}

	public function get_title() {
		return 'FS Galeria de Fotos';
	}

	public function get_icon() {
		return 'eicon-gallery-grid';
	}

	public function get_categories() {
		return array( 'general' );
	}

	public function get_keywords() {
		return array( 'galeria', 'fotos', 'imovel', 'jetengine', 'mosaico', 'watermark' );
	}

	public function get_style_depends() {
		return array( 'fs-imob-gallery' );
	}

	public function get_script_depends() {
		return array( 'fs-imob-gallery' );
	}

	protected function register_controls() {
		$this->register_content_controls();
		$this->register_layout_controls();
		$this->register_style_controls();
	}

	private function register_content_controls() {
		$this->start_controls_section(
			'section_images',
			array(
				'label' => 'Configuracoes da galeria',
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'gallery_source',
			array(
				'label'   => 'Origem das fotos',
				'type'    => Controls_Manager::SELECT,
				'default' => 'manual',
				'options' => array(
					'manual' => 'Galeria Manual',
					'meta'   => 'JetEngine / Meta Field',
				),
			)
		);

		$this->add_control(
			'gallery_type',
			array(
				'label'   => 'Tipo de Galeria',
				'type'    => Controls_Manager::SELECT,
				'default' => 'grid',
				'options' => array(
					'grid'    => 'Grid',
					'masonry' => 'Masonry',
				),
			)
		);

		$this->add_control(
			'gallery',
			array(
				'label'       => 'Galeria',
				'type'        => Controls_Manager::GALLERY,
				'description' => 'Use imagens manuais ou uma tag dinamica do Elementor/JetEngine.',
				'dynamic'     => array( 'active' => true ),
				'condition'   => array( 'gallery_source' => 'manual' ),
			)
		);

		$this->add_control(
			'jet_meta_key',
			array(
				'label'       => 'Campo meta do JetEngine',
				'type'        => Controls_Manager::TEXT,
				'description' => 'Opcional. Informe a chave do campo quando as imagens estiverem salvas no post atual.',
				'condition'   => array( 'gallery_source' => 'meta' ),
			)
		);

		$this->add_control(
			'manual_post_id',
			array(
				'label'       => 'Post ID manual',
				'type'        => Controls_Manager::NUMBER,
				'description' => 'Opcional. Use quando o template single nao conseguir detectar o imovel automaticamente.',
				'min'         => 1,
			)
		);

		$this->add_control(
			'meta_fallback_lookup',
			array(
				'label'        => 'Buscar post com campo se o atual estiver vazio',
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => 'Sim',
				'label_off'    => 'Nao',
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => array( 'gallery_source' => 'meta' ),
			)
		);

		$this->add_control(
			'image_size',
			array(
				'label'   => 'Tamanho da imagem',
				'type'    => Controls_Manager::SELECT,
				'default' => 'large',
				'options' => $this->get_image_size_options(),
			)
		);

		$this->add_control(
			'gallery_order',
			array(
				'label'   => 'Ordem das fotos',
				'type'    => Controls_Manager::SELECT,
				'default' => 'ASC',
				'options' => array(
					'ASC'    => 'ASC',
					'DESC'   => 'DESC',
					'RANDOM' => 'Random',
				),
			)
		);

		$this->add_control(
			'enable_lightbox',
			array(
				'label'        => 'Ativar lightbox',
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => 'Sim',
				'label_off'    => 'Nao',
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_camera_icon',
			array(
				'label'        => 'Exibir icone de camera',
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => 'Sim',
				'label_off'    => 'Nao',
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_watermark',
			array(
				'label' => "Marca d'agua",
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'watermark_enabled',
			array(
				'label'        => "Ativar marca d'agua",
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => 'Sim',
				'label_off'    => 'Nao',
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'watermark_source',
			array(
				'label'     => "Origem da marca d'agua",
				'type'      => Controls_Manager::SELECT,
				'default'   => 'manual',
				'options'   => array(
					'manual' => 'Imagem padrao',
					'meta'   => 'JetEngine / Meta Field',
				),
				'condition' => array( 'watermark_enabled' => 'yes' ),
			)
		);

		$this->add_control(
			'watermark_image',
			array(
				'label'     => "Imagem da marca d'agua",
				'type'      => Controls_Manager::MEDIA,
				'dynamic'   => array( 'active' => true ),
				'condition' => array(
					'watermark_enabled' => 'yes',
					'watermark_source'  => 'manual',
				),
			)
		);

		$this->add_control(
			'watermark_meta_key',
			array(
				'label'       => "Campo JetEngine da marca d'agua",
				'type'        => Controls_Manager::TEXT,
				'description' => 'Informe a chave do campo de imagem do JetEngine. Tambem aceita a tag dinamica Custom Image no campo de imagem padrao.',
				'condition'   => array(
					'watermark_enabled' => 'yes',
					'watermark_source'  => 'meta',
				),
			)
		);

		$this->add_control(
			'watermark_alt',
			array(
				'label'     => "Texto alternativo da marca d'agua",
				'type'      => Controls_Manager::TEXT,
				'default'   => "Marca d'agua",
				'condition' => array( 'watermark_enabled' => 'yes' ),
			)
		);

		$this->add_control(
			'watermark_display',
			array(
				'label'     => "Exibir marca d'agua",
				'type'      => Controls_Manager::SELECT,
				'default'   => 'mosaic',
				'options'   => array(
					'mosaic'   => 'Apenas no mosaico',
					'lightbox' => 'Apenas no lightbox',
					'both'     => 'No mosaico e no lightbox',
				),
				'condition' => array( 'watermark_enabled' => 'yes' ),
			)
		);

		$this->add_control(
			'watermark_apply_to',
			array(
				'label'     => "Aplicar marca d'agua em",
				'type'      => Controls_Manager::SELECT,
				'default'   => 'all',
				'options'   => array(
					'all'    => 'Todas as imagens',
					'main'   => 'Apenas imagem principal',
					'thumbs' => 'Apenas miniaturas',
				),
				'condition' => array( 'watermark_enabled' => 'yes' ),
			)
		);

		$this->end_controls_section();
	}

	private function register_layout_controls() {
		$this->start_controls_section(
			'section_layout_structure',
			array(
				'label' => 'Estrutura',
				'tab'   => Controls_Manager::TAB_LAYOUT,
			)
		);

		$this->add_control(
			'main_position',
			array(
				'label'   => 'Posicao da imagem grande',
				'type'    => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => array(
					'left'   => 'Esquerda',
					'right'  => 'Direita',
					'top'    => 'Em cima',
					'bottom' => 'Embaixo',
				),
			)
		);

		$this->add_responsive_control(
			'main_width',
			array(
				'label'      => 'Largura da imagem principal no desktop',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%' ),
				'default'    => array(
					'unit' => '%',
					'size' => 50,
				),
				'range'      => array(
					'%' => array(
						'min' => 30,
						'max' => 70,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .fs-imob-gallery' => '--fs-main-width: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array( 'main_position' => array( 'left', 'right' ) ),
			)
		);

		$this->add_responsive_control(
			'gallery_height',
			array(
				'label'      => 'Altura',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'vh', 'rem' ),
				'default'    => array(
					'unit' => 'px',
					'size' => 630,
				),
				'tablet_default' => array(
					'unit' => 'px',
					'size' => 480,
				),
				'mobile_default' => array(
					'unit' => 'px',
					'size' => 320,
				),
				'range'      => array(
					'px' => array(
						'min' => 160,
						'max' => 1200,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .fs-imob-gallery' => '--fs-gallery-height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'masonry_columns',
			array(
				'label'     => 'Colunas no Masonry',
				'type'      => Controls_Manager::NUMBER,
				'default'   => 4,
				'tablet_default' => 3,
				'mobile_default' => 3,
				'min'       => 3,
				'max'       => 10,
				'step'      => 1,
				'selectors' => array(
					'{{WRAPPER}} .fs-imob-gallery' => '--fs-masonry-columns: {{VALUE}};',
				),
				'condition' => array( 'gallery_type' => 'masonry' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_layout_spacing',
			array(
				'label' => 'Espacamento',
				'tab'   => Controls_Manager::TAB_LAYOUT,
			)
		);

		$this->add_responsive_control(
			'main_gap',
			array(
				'label'      => 'Espacamento horizontal entre colunas',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem' ),
				'default'    => array(
					'unit' => 'px',
					'size' => 6,
				),
				'selectors'  => array(
					'{{WRAPPER}} .fs-imob-gallery' => '--fs-main-gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'thumb_gap_x',
			array(
				'label'      => 'Espacamento horizontal entre miniaturas',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem' ),
				'default'    => array(
					'unit' => 'px',
					'size' => 6,
				),
				'selectors'  => array(
					'{{WRAPPER}} .fs-imob-gallery' => '--fs-thumb-gap-x: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'thumb_gap_y',
			array(
				'label'      => 'Espacamento vertical entre miniaturas',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem' ),
				'default'    => array(
					'unit' => 'px',
					'size' => 6,
				),
				'selectors'  => array(
					'{{WRAPPER}} .fs-imob-gallery' => '--fs-thumb-gap-y: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'widget_padding',
			array(
				'label'      => 'Padding do widget',
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', 'rem', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .fs-imob-gallery' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'widget_margin',
			array(
				'label'      => 'Margin do widget',
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', 'rem', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .fs-imob-gallery' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_layout_mobile',
			array(
				'label' => 'Mobile',
				'tab'   => Controls_Manager::TAB_LAYOUT,
			)
		);

		$this->add_control(
			'mobile_order',
			array(
				'label'   => 'Ordem no mobile',
				'type'    => Controls_Manager::SELECT,
				'default' => 'main_first',
				'options' => array(
					'main_first'   => 'Imagem principal primeiro',
					'thumbs_first' => 'Miniaturas primeiro',
				),
			)
		);

		$this->add_control(
			'mobile_thumb_columns',
			array(
				'label'   => 'Colunas das miniaturas no mobile',
				'type'    => Controls_Manager::SELECT,
				'default' => '2',
				'options' => array(
					'1' => '1 coluna',
					'2' => '2 colunas',
				),
			)
		);

		$this->add_responsive_control(
			'mobile_gap_x',
			array(
				'label'      => 'Espacamento horizontal mobile',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem' ),
				'default'    => array(
					'unit' => 'px',
					'size' => 6,
				),
				'selectors'  => array(
					'{{WRAPPER}} .fs-imob-gallery' => '--fs-mobile-gap-x: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'mobile_gap_y',
			array(
				'label'      => 'Espacamento vertical mobile',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem' ),
				'default'    => array(
					'unit' => 'px',
					'size' => 6,
				),
				'selectors'  => array(
					'{{WRAPPER}} .fs-imob-gallery' => '--fs-mobile-gap-y: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	private function register_style_controls() {
		$this->start_controls_section(
			'section_style_images',
			array(
				'label' => 'Imagens',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'image_border_radius',
			array(
				'label'      => 'Border radius',
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .fs-imob-gallery__item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'hover_overlay_color',
			array(
				'label'     => 'Overlay no hover',
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(0,0,0,0.22)',
				'selectors' => array(
					'{{WRAPPER}} .fs-imob-gallery__item::after' => 'background: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_counter',
			array(
				'label' => 'Overlay +N',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'counter_bg',
			array(
				'label'     => 'Fundo',
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(0,0,0,0.58)',
				'selectors' => array(
					'{{WRAPPER}} .fs-imob-gallery__counter' => 'background: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'counter_color',
			array(
				'label'     => 'Cor',
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .fs-imob-gallery__counter' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'counter_typography',
				'selector' => '{{WRAPPER}} .fs-imob-gallery__counter',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_watermark',
			array(
				'label' => "Marca d'agua",
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'watermark_opacity',
			array(
				'label'     => "Opacidade da marca d'agua",
				'type'      => Controls_Manager::SLIDER,
				'default'   => array( 'size' => 0.35 ),
				'range'     => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1,
						'step' => 0.01,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .fs-imob-gallery__watermark' => 'opacity: {{SIZE}};',
				),
			)
		);

		$this->add_responsive_control(
			'watermark_width',
			array(
				'label'      => "Tamanho da marca d'agua",
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'vw' ),
				'default'    => array(
					'unit' => 'px',
					'size' => 180,
				),
				'tablet_default' => array(
					'unit' => 'px',
					'size' => 140,
				),
				'mobile_default' => array(
					'unit' => 'px',
					'size' => 100,
				),
				'range'      => array(
					'px' => array(
						'min'  => 20,
						'max'  => 600,
						'step' => 1,
					),
					'%'  => array(
						'min'  => 5,
						'max'  => 100,
						'step' => 1,
					),
					'vw' => array(
						'min'  => 2,
						'max'  => 80,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .fs-imob-gallery__watermark' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'watermark_max_width',
			array(
				'label'      => "Largura maxima da marca d'agua",
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'vw' ),
				'default'    => array(
					'unit' => '%',
					'size' => 40,
				),
				'selectors'  => array(
					'{{WRAPPER}} .fs-imob-gallery__watermark' => 'max-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'watermark_rotation',
			array(
				'label'      => "Rotacao da marca d'agua",
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'deg' ),
				'default'    => array(
					'unit' => 'deg',
					'size' => 0,
				),
				'range'      => array(
					'deg' => array(
						'min' => -180,
						'max' => 180,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .fs-imob-gallery__watermark' => 'transform: translate(-50%, -50%) rotate({{SIZE}}deg);',
				),
			)
		);

		$this->add_control(
			'watermark_blend_mode',
			array(
				'label'     => "Blend mode da marca d'agua",
				'type'      => Controls_Manager::SELECT,
				'default'   => 'normal',
				'options'   => array(
					'normal'     => 'Normal',
					'multiply'   => 'Multiply',
					'screen'     => 'Screen',
					'overlay'    => 'Overlay',
					'soft-light' => 'Soft Light',
				),
				'selectors' => array(
					'{{WRAPPER}} .fs-imob-gallery__watermark' => 'mix-blend-mode: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'watermark_z_index',
			array(
				'label'     => "Z-index da marca d'agua",
				'type'      => Controls_Manager::NUMBER,
				'default'   => 2,
				'selectors' => array(
					'{{WRAPPER}} .fs-imob-gallery__watermark' => 'z-index: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'watermark_hover_opacity',
			array(
				'label'     => 'Opacidade no hover',
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1,
						'step' => 0.01,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .fs-imob-gallery__item:hover .fs-imob-gallery__watermark' => 'opacity: {{SIZE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$raw_settings = $this->get_settings();
		wp_enqueue_style( 'fs-imob-gallery' );
		wp_enqueue_script( 'fs-imob-gallery' );

		$gallery_data = $this->get_gallery_images( $settings, $raw_settings );
		$images       = $gallery_data['images'];

		if ( empty( $images ) ) {
			if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
				$this->render_empty_debug( $gallery_data['debug'] );
			}
			return;
		}

		$watermark_url = $this->get_watermark_url( $settings, $raw_settings );
		$watermark_on  = 'yes' === ( $settings['watermark_enabled'] ?? '' ) && ! empty( $watermark_url );

		if ( 'yes' === ( $settings['watermark_enabled'] ?? '' ) && empty( $watermark_url ) ) {
			if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
				echo '<div class="fs-imob-gallery__notice">Selecione uma imagem de marca d\'agua.</div>';
			}
		}

		$position       = $settings['main_position'] ?? 'left';
		$mobile_order   = $settings['mobile_order'] ?? 'main_first';
		$mobile_columns = $settings['mobile_thumb_columns'] ?? '2';
		$gallery_type   = $settings['gallery_type'] ?? 'grid';
		$lightbox       = 'yes' === ( $settings['enable_lightbox'] ?? '' );
		$display        = $settings['watermark_display'] ?? 'mosaic';
		$total          = count( $images );
		$root_classes   = array(
			'fs-imob-gallery',
			'fs-imob-gallery--type-' . sanitize_html_class( $gallery_type ),
			'fs-imob-gallery--main-' . sanitize_html_class( $position ),
			'fs-imob-gallery--mobile-' . sanitize_html_class( $mobile_order ),
			'fs-imob-gallery--mobile-cols-' . sanitize_html_class( $mobile_columns ),
		);

		if ( $watermark_on ) {
			$root_classes[] = 'fs-imob-gallery--watermark-enabled';
		}

		if ( 1 === $total ) {
			$root_classes[] = 'fs-imob-gallery--single';
		}

		$attrs = array(
			'class'           => implode( ' ', $root_classes ),
			'data-lightbox'   => $lightbox ? 'yes' : 'no',
			'data-watermark'  => $watermark_on && in_array( $display, array( 'lightbox', 'both' ), true ) ? 'yes' : 'no',
			'data-wm-src'     => $watermark_url,
			'data-wm-alt'     => $settings['watermark_alt'] ?? "Marca d'agua",
			'data-wm-opacity' => $this->slider_size( $settings, 'watermark_opacity', '0.35' ),
			'data-wm-width'   => $this->slider_css_value( $settings, 'watermark_width', '180px' ),
			'data-wm-max'     => $this->slider_css_value( $settings, 'watermark_max_width', '40%' ),
			'data-wm-rotate'  => $this->slider_size( $settings, 'watermark_rotation', '0' ),
			'data-wm-blend'   => $settings['watermark_blend_mode'] ?? 'normal',
			'data-wm-z'       => $settings['watermark_z_index'] ?? '2',
		);

		echo '<div ' . $this->render_attrs( $attrs ) . '>';

		if ( 'masonry' === $gallery_type ) {
			$this->render_masonry_gallery( $images, $settings, $watermark_on, $watermark_url, $lightbox );
			echo '<script type="application/json" class="fs-imob-gallery__data">' . wp_json_encode( $images, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT ) . '</script>';
			echo '</div>';
			return;
		}

		echo '<div class="fs-imob-gallery__mosaic">';
		$this->render_item( $images[0], 0, 'main', $settings, $watermark_on, $watermark_url, $lightbox );

		$thumbs     = array_slice( $images, 1, 4 );
		$last_index = count( $thumbs ) - 1;

		if ( ! empty( $thumbs ) ) {
			echo '<div class="fs-imob-gallery__thumbs">';
			foreach ( $thumbs as $i => $image ) {
				$remaining = ( $i === $last_index && $total > 5 ) ? $total - 5 : 0;
				$this->render_item( $image, $i + 1, 'thumb', $settings, $watermark_on, $watermark_url, $lightbox, $remaining );
			}
			echo '</div>';
		}

		echo '</div>';
		echo '<script type="application/json" class="fs-imob-gallery__data">' . wp_json_encode( $images, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT ) . '</script>';
		echo '</div>';
	}

	private function render_masonry_gallery( $images, $settings, $watermark_on, $watermark_url, $lightbox ) {
		echo '<div class="fs-imob-gallery__masonry">';
		foreach ( $images as $index => $image ) {
			$this->render_item( $image, $index, 'masonry', $settings, $watermark_on, $watermark_url, $lightbox );
		}
		echo '</div>';
	}

	private function render_item( $image, $index, $type, $settings, $watermark_on, $watermark_url, $lightbox, $remaining = 0 ) {
		$item_classes = array( 'fs-imob-gallery__item', 'fs-imob-gallery__item--' . $type );
		$tag          = $lightbox ? 'button' : 'div';
		$attrs        = array(
			'class'      => implode( ' ', $item_classes ),
			'data-index' => (string) $index,
		);

		if ( $lightbox ) {
			$attrs['type'] = 'button';
		}

		echo '<' . esc_html( $tag ) . ' ' . $this->render_attrs( $attrs ) . '>';
		echo '<img class="fs-imob-gallery__image" src="' . esc_url( $image['thumb'] ) . '" alt="' . esc_attr( $image['alt'] ) . '" loading="' . ( 0 === $index ? 'eager' : 'lazy' ) . '">';

		if ( $this->should_render_watermark( $settings, $watermark_on, $type ) ) {
			echo '<span class="fs-imob-gallery__watermark" aria-hidden="true">';
			echo '<img class="fs-imob-gallery__watermark-image" src="' . esc_url( $watermark_url ) . '" alt="' . esc_attr( $settings['watermark_alt'] ?? "Marca d'agua" ) . '">';
			echo '</span>';
		}

		if ( $remaining > 0 ) {
			echo '<span class="fs-imob-gallery__counter">+' . esc_html( (string) $remaining ) . '</span>';
		}

		if ( 'yes' === ( $settings['show_camera_icon'] ?? '' ) && 0 === $index ) {
			echo '<span class="fs-imob-gallery__camera" aria-hidden="true"><svg viewBox="0 0 24 24" focusable="false"><path d="M20 5h-3.2l-1.7-2H8.9L7.2 5H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2Zm-8 13a5 5 0 1 1 0-10 5 5 0 0 1 0 10Zm0-2a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"/></svg></span>';
		}

		echo '</' . esc_html( $tag ) . '>';
	}

	private function should_render_watermark( $settings, $watermark_on, $type ) {
		if ( ! $watermark_on ) {
			return false;
		}

		$display = $settings['watermark_display'] ?? 'mosaic';
		if ( ! in_array( $display, array( 'mosaic', 'both' ), true ) ) {
			return false;
		}

		$apply = $settings['watermark_apply_to'] ?? 'all';
		return 'all' === $apply || ( 'main' === $apply && 'main' === $type ) || ( 'thumbs' === $apply && in_array( $type, array( 'thumb', 'masonry' ), true ) );
	}

	private function get_gallery_images( $settings, $raw_settings = array() ) {
		$post_id      = $this->detect_current_post_id( $settings );
		$source       = $settings['gallery_source'] ?? 'manual';
		$meta_key     = isset( $settings['jet_meta_key'] ) ? trim( (string) $settings['jet_meta_key'] ) : '';
		$dynamic_key  = $this->extract_dynamic_gallery_field( $raw_settings );
		if ( '' === $meta_key && '' !== $dynamic_key ) {
			$meta_key = $dynamic_key;
		}
		$raw_data     = null;
		$meta_data    = null;
		$meta_type    = 'campo nao configurado';
		$fallback_post_id = 0;
		$meta_lookup  = array(
			'found_key'      => '',
			'available_keys' => array(),
			'candidate_keys' => array(),
		);
		$used_source  = $source;
		$candidates   = array();

		if ( 'meta' === $source ) {
			$meta_lookup = $this->get_meta_lookup( $post_id, $meta_key );
			$meta_data   = $meta_lookup['value'];
			$meta_type   = $this->data_type( $meta_data );
			$raw_data    = $meta_data;
			$candidates  = $this->extract_image_candidates( $meta_data );
		} else {
			$raw_data    = $settings['gallery'] ?? array();
			$candidates  = $this->extract_image_candidates( $raw_data );
		}

		if ( empty( $candidates ) && 'manual' === $source && '' !== $meta_key ) {
			$meta_lookup = $this->get_meta_lookup( $post_id, $meta_key );
			$meta_data   = $meta_lookup['value'];
			$meta_type   = $this->data_type( $meta_data );
			$raw_data    = $meta_data;
			$candidates  = $this->extract_image_candidates( $meta_data );
			$used_source = 'meta';
		}

		if ( empty( $candidates ) && '' !== $meta_key && 'yes' === ( $settings['meta_fallback_lookup'] ?? 'yes' ) ) {
			$fallback_lookup = $this->find_post_with_meta_gallery( $meta_key, $post_id );
			if ( ! empty( $fallback_lookup['post_id'] ) ) {
				$fallback_post_id = absint( $fallback_lookup['post_id'] );
				$post_id          = $fallback_post_id;
				$meta_lookup      = array_merge( $meta_lookup, $fallback_lookup );
				$meta_data        = $fallback_lookup['value'];
				$meta_type        = $this->data_type( $meta_data );
				$raw_data         = $meta_data;
				$candidates       = $this->extract_image_candidates( $meta_data );
				$used_source      = 'meta-fallback';
			}
		}

		if ( empty( $candidates ) && 'meta' === $source && ! empty( $settings['gallery'] ) ) {
			$raw_data    = $settings['gallery'];
			$candidates  = $this->extract_image_candidates( $raw_data );
			$used_source = 'manual';
		}

		$images = $this->normalize_images( $candidates, $post_id );
		$images = $this->apply_gallery_order( $images, $settings['gallery_order'] ?? 'ASC', $post_id );

		return array(
			'images' => $images,
			'debug'  => array(
				'render_executed'   => 'sim',
				'post_id'           => $post_id,
				'field'             => $meta_key,
				'dynamic_field'     => $dynamic_key,
				'fallback_post_id'  => $fallback_post_id,
				'selected_source'   => $source,
				'used_source'       => $used_source,
				'meta_type'         => $meta_type,
				'found_meta_key'    => $meta_lookup['found_key'] ?? '',
				'candidate_keys'    => $meta_lookup['candidate_keys'] ?? array(),
				'available_keys'    => $meta_lookup['available_keys'] ?? array(),
				'before_count'      => count( $candidates ),
				'after_count'       => count( $images ),
				'sample'            => $this->safe_debug_sample( $raw_data ),
			),
		);
	}

	private function detect_current_post_id( $settings ) {
		$manual_id = absint( $settings['manual_post_id'] ?? 0 );
		if ( $manual_id ) {
			return $manual_id;
		}

		$jet_object_id = $this->get_jet_engine_current_object_id();
		if ( $jet_object_id ) {
			return $jet_object_id;
		}

		$the_id = absint( get_the_ID() );
		if ( $the_id && 'elementor_library' !== get_post_type( $the_id ) ) {
			return $the_id;
		}

		$queried_id = absint( get_queried_object_id() );
		if ( $queried_id && 'elementor_library' !== get_post_type( $queried_id ) ) {
			return $queried_id;
		}

		if ( class_exists( '\Elementor\Plugin' ) && \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			$preview_id = $this->detect_elementor_preview_post_id();
			if ( $preview_id ) {
				return $preview_id;
			}
		}

		global $post;
		return absint( $post->ID ?? 0 );
	}

	private function get_jet_engine_current_object_id() {
		if ( ! function_exists( 'jet_engine' ) || empty( jet_engine()->listings ) || empty( jet_engine()->listings->data ) ) {
			return 0;
		}

		$data = jet_engine()->listings->data;

		if ( method_exists( $data, 'get_current_object_id' ) ) {
			$object_id = absint( $data->get_current_object_id() );
			if ( $object_id && 'elementor_library' !== get_post_type( $object_id ) ) {
				return $object_id;
			}
		}

		if ( method_exists( $data, 'get_current_object' ) ) {
			$object = $data->get_current_object();
			if ( $object instanceof \WP_Post && 'elementor_library' !== get_post_type( $object->ID ) ) {
				return absint( $object->ID );
			}
		}

		return 0;
	}

	private function detect_elementor_preview_post_id() {
		$request_keys = array( 'preview_id', 'post_id', 'editor_post_id', 'elementor-preview', 'post' );
		foreach ( $request_keys as $key ) {
			if ( isset( $_REQUEST[ $key ] ) && ! is_array( $_REQUEST[ $key ] ) ) {
				$value = absint( wp_unslash( $_REQUEST[ $key ] ) );
				if ( $value ) {
					return $value;
				}
			}
		}

		if ( ! class_exists( '\Elementor\Plugin' ) || empty( \Elementor\Plugin::$instance->documents ) ) {
			return 0;
		}

		$document = \Elementor\Plugin::$instance->documents->get_current();
		if ( ! $document ) {
			return 0;
		}

		foreach ( array( 'preview_id', 'post_id' ) as $setting_key ) {
			if ( method_exists( $document, 'get_settings' ) ) {
				$value = absint( $document->get_settings( $setting_key ) );
				if ( $value ) {
					return $value;
				}
			}
		}

		if ( method_exists( $document, 'get_main_id' ) ) {
			return absint( $document->get_main_id() );
		}

		return 0;
	}

	private function extract_dynamic_gallery_field( $raw_settings ) {
		$settings = $this->extract_dynamic_tag_settings( $raw_settings, 'gallery' );

		foreach ( array( 'gallery_field', 'field', 'meta_field', 'dynamic_field_post_meta', 'dynamic_field_post_meta_custom' ) as $key ) {
			if ( ! empty( $settings[ $key ] ) && is_string( $settings[ $key ] ) ) {
				return trim( $settings[ $key ] );
			}
		}

		return '';
	}

	private function extract_dynamic_image_field( $raw_settings, $control_name ) {
		$settings = $this->extract_dynamic_tag_settings( $raw_settings, $control_name );

		foreach ( array( 'img_field', 'field', 'meta_field', 'dynamic_field_post_meta', 'dynamic_field_post_meta_custom' ) as $key ) {
			if ( ! empty( $settings[ $key ] ) && is_string( $settings[ $key ] ) ) {
				return trim( $settings[ $key ] );
			}
		}

		return '';
	}

	private function extract_dynamic_tag_settings( $raw_settings, $control_name ) {
		if ( empty( $raw_settings['__dynamic__'][ $control_name ] ) ) {
			return array();
		}

		$tag_text = $raw_settings['__dynamic__'][ $control_name ];
		$settings = array();

		if ( class_exists( '\Elementor\Plugin' ) && ! empty( \Elementor\Plugin::$instance->dynamic_tags ) && method_exists( \Elementor\Plugin::$instance->dynamic_tags, 'tag_text_to_tag_data' ) ) {
			$tag_data = \Elementor\Plugin::$instance->dynamic_tags->tag_text_to_tag_data( $tag_text );
			if ( ! empty( $tag_data['settings'] ) && is_array( $tag_data['settings'] ) ) {
				$settings = $tag_data['settings'];
			}
		}

		if ( empty( $settings ) && preg_match( '/settings="([^"]+)"/', $tag_text, $matches ) ) {
			$decoded = json_decode( urldecode( html_entity_decode( $matches[1], ENT_QUOTES, 'UTF-8' ) ), true );
			if ( is_array( $decoded ) ) {
				$settings = $decoded;
			}
		}

		return $settings;
	}

	private function get_meta_lookup( $post_id, $meta_key ) {
		$result = array(
			'value'          => null,
			'found_key'      => '',
			'candidate_keys' => $this->build_meta_key_candidates( $meta_key ),
			'available_keys' => array(),
		);

		if ( ! $post_id ) {
			return $result;
		}

		$all_meta = get_post_meta( $post_id );
		if ( is_array( $all_meta ) ) {
			$result['available_keys'] = array_keys( $all_meta );
		}

		foreach ( $result['candidate_keys'] as $candidate_key ) {
			if ( '' === $candidate_key ) {
				continue;
			}

			$value = get_post_meta( $post_id, $candidate_key, true );
			if ( $this->meta_value_has_content( $value ) ) {
				$result['value']     = $value;
				$result['found_key'] = $candidate_key;
				return $result;
			}
		}

		if ( '' !== $meta_key && ! empty( $all_meta ) ) {
			$wanted = $this->normalize_meta_key_for_compare( $meta_key );
			foreach ( $all_meta as $existing_key => $values ) {
				if ( $wanted !== $this->normalize_meta_key_for_compare( $existing_key ) ) {
					continue;
				}

				$value = get_post_meta( $post_id, $existing_key, true );
				if ( $this->meta_value_has_content( $value ) ) {
					$result['value']     = $value;
					$result['found_key'] = $existing_key;
					return $result;
				}
			}
		}

		if ( '' === $meta_key && ! empty( $all_meta ) ) {
			foreach ( $all_meta as $existing_key => $values ) {
				$value      = get_post_meta( $post_id, $existing_key, true );
				$candidates = $this->extract_image_candidates( $value );
				$images     = $this->normalize_images( $candidates, $post_id );
				if ( ! empty( $images ) ) {
					$result['value']     = $value;
					$result['found_key'] = $existing_key;
					return $result;
				}
			}
		}

		if ( '' !== $meta_key ) {
			$result['value'] = get_post_meta( $post_id, $meta_key, true );
		}

		return $result;
	}

	private function find_post_with_meta_gallery( $meta_key, $exclude_post_id = 0 ) {
		global $wpdb;

		$result = array(
			'value'     => null,
			'found_key' => '',
			'post_id'   => 0,
		);

		$candidate_keys = $this->build_meta_key_candidates( $meta_key );
		if ( empty( $candidate_keys ) ) {
			return $result;
		}

		$placeholders = implode( ', ', array_fill( 0, count( $candidate_keys ), '%s' ) );
		$params       = $candidate_keys;
		$sql          = "SELECT post_id, meta_key, meta_value FROM {$wpdb->postmeta} WHERE meta_key IN ($placeholders) AND meta_value <> ''";

		if ( $exclude_post_id ) {
			$sql      .= ' AND post_id <> %d';
			$params[] = absint( $exclude_post_id );
		}

		$sql .= ' ORDER BY post_id DESC LIMIT 50';
		$rows = $wpdb->get_results( $wpdb->prepare( $sql, $params ) );

		foreach ( $rows as $row ) {
			$candidates = $this->extract_image_candidates( $row->meta_value );
			$images     = $this->normalize_images( $candidates, absint( $row->post_id ) );
			if ( empty( $images ) ) {
				continue;
			}

			$result['value']     = $row->meta_value;
			$result['found_key'] = $row->meta_key;
			$result['post_id']   = absint( $row->post_id );
			return $result;
		}

		return $result;
	}

	private function build_meta_key_candidates( $meta_key ) {
		$meta_key = trim( (string) $meta_key );
		if ( '' === $meta_key ) {
			return array();
		}

		$candidates = array(
			$meta_key,
			sanitize_key( $meta_key ),
			sanitize_title( $meta_key ),
			strtolower( $meta_key ),
		);

		foreach ( $candidates as $candidate ) {
			if ( '' !== $candidate && '_' !== $candidate[0] ) {
				$candidates[] = '_' . $candidate;
			}
		}

		return array_values( array_unique( array_filter( $candidates, 'strlen' ) ) );
	}

	private function normalize_meta_key_for_compare( $meta_key ) {
		$meta_key = trim( strtolower( (string) $meta_key ) );
		$meta_key = ltrim( $meta_key, '_' );
		return preg_replace( '/[^a-z0-9]+/', '', $meta_key );
	}

	private function meta_value_has_content( $value ) {
		if ( is_array( $value ) ) {
			return ! empty( $value );
		}

		if ( is_object( $value ) ) {
			return ! empty( get_object_vars( $value ) );
		}

		return '' !== trim( (string) $value );
	}

	private function extract_image_candidates( $data ) {
		if ( null === $data || '' === $data ) {
			return array();
		}

		if ( is_object( $data ) ) {
			$data = get_object_vars( $data );
		}

		if ( is_string( $data ) ) {
			$value        = trim( $data );
			$unserialized = maybe_unserialize( $value );
			if ( $unserialized !== $value ) {
				return $this->extract_image_candidates( $unserialized );
			}

			$decoded = json_decode( $value, true );
			if ( JSON_ERROR_NONE === json_last_error() && null !== $decoded ) {
				return $this->extract_image_candidates( $decoded );
			}

			if ( false !== strpos( $value, ',' ) ) {
				$candidates = array();
				foreach ( array_map( 'trim', explode( ',', $value ) ) as $part ) {
					$candidates = array_merge( $candidates, $this->extract_image_candidates( $part ) );
				}
				return $candidates;
			}

			return array( $value );
		}

		if ( is_numeric( $data ) ) {
			return array( $data );
		}

		if ( ! is_array( $data ) ) {
			return array();
		}

		$candidates = array();
		if ( $this->array_has_image_keys( $data ) ) {
			return array( $data );
		}

		foreach ( $data as $value ) {
			$candidates = array_merge( $candidates, $this->extract_image_candidates( $value ) );
		}
		return $candidates;
	}

	private function array_has_image_keys( $data ) {
		foreach ( array( 'id', 'ID', 'url', 'full', 'thumb', 'src', 'image', 'attachment_id' ) as $key ) {
			if ( array_key_exists( $key, $data ) && '' !== $data[ $key ] && null !== $data[ $key ] ) {
				return true;
			}
		}

		return false;
	}

	private function normalize_images( $candidates, $post_id ) {
		$images = array();
		$seen   = array();

		foreach ( $candidates as $candidate ) {
			$image = $this->normalize_image_candidate( $candidate, $post_id );
			if ( empty( $image ) ) {
				continue;
			}

			$key = $image['id'] ? 'id:' . $image['id'] : 'url:' . $image['full'];
			if ( isset( $seen[ $key ] ) ) {
				continue;
			}

			$seen[ $key ] = true;
			$images[]     = $image;
		}

		return $images;
	}

	private function normalize_image_candidate( $candidate, $post_id ) {
		if ( is_object( $candidate ) ) {
			$candidate = get_object_vars( $candidate );
		}

		if ( is_array( $candidate ) ) {
			foreach ( array( 'id', 'ID', 'attachment_id' ) as $id_key ) {
				if ( ! empty( $candidate[ $id_key ] ) ) {
					$image = $this->image_from_id( $candidate[ $id_key ], $post_id );
					if ( $image ) {
						return $image;
					}
				}
			}

			foreach ( array( 'url', 'full', 'thumb', 'src' ) as $url_key ) {
				if ( ! empty( $candidate[ $url_key ] ) && is_string( $candidate[ $url_key ] ) ) {
					$image = $this->image_from_url( $candidate[ $url_key ], $post_id );
					if ( $image ) {
						return $image;
					}
				}
			}

			if ( ! empty( $candidate['image'] ) ) {
				return $this->normalize_image_candidate( $candidate['image'], $post_id );
			}

			return null;
		}

		if ( is_numeric( $candidate ) ) {
			return $this->image_from_id( $candidate, $post_id );
		}

		if ( is_string( $candidate ) ) {
			$value = trim( $candidate );
			if ( is_numeric( $value ) ) {
				return $this->image_from_id( $value, $post_id );
			}

			return $this->image_from_url( $value, $post_id );
		}

		return null;
	}

	private function image_from_id( $id, $post_id ) {
		$id = absint( $id );
		if ( ! $id ) {
			return null;
		}

		$full  = wp_get_attachment_image_url( $id, 'full' );
		$thumb = wp_get_attachment_image_url( $id, 'large' );

		if ( ! $full ) {
			$full = wp_get_attachment_url( $id );
		}

		if ( ! $thumb ) {
			$thumb = $full;
		}

		if ( ! $full ) {
			return null;
		}

		$alt = get_post_meta( $id, '_wp_attachment_image_alt', true );
		if ( '' === $alt ) {
			$alt = get_the_title( $id );
		}
		if ( '' === $alt ) {
			$alt = $post_id ? get_the_title( $post_id ) : '';
		}

		return array(
			'id'    => $id,
			'full'  => esc_url_raw( $full ),
			'thumb' => esc_url_raw( $thumb ),
			'alt'   => wp_strip_all_tags( (string) $alt ),
		);
	}

	private function image_from_url( $url, $post_id ) {
		$url = esc_url_raw( $url );
		if ( empty( $url ) ) {
			return null;
		}

		$alt = $post_id ? get_the_title( $post_id ) : '';

		return array(
			'id'    => 0,
			'full'  => $url,
			'thumb' => $url,
			'alt'   => wp_strip_all_tags( (string) $alt ),
		);
	}

	private function apply_gallery_order( $images, $order, $post_id ) {
		$order = strtoupper( (string) $order );

		if ( 'DESC' === $order ) {
			return array_reverse( $images );
		}

		if ( 'RANDOM' === $order ) {
			$widget_id = method_exists( $this, 'get_id' ) ? $this->get_id() : 'widget';
			usort(
				$images,
				function ( $a, $b ) use ( $post_id, $widget_id ) {
					$a_key = md5( $post_id . '|' . $widget_id . '|' . ( $a['id'] ?: $a['full'] ) );
					$b_key = md5( $post_id . '|' . $widget_id . '|' . ( $b['id'] ?: $b['full'] ) );
					return strcmp( $a_key, $b_key );
				}
			);
		}

		return $images;
	}

	private function render_empty_debug( $debug ) {
		echo '<div class="fs-imob-gallery__debug">';
		echo '<strong>Nenhuma imagem encontrada. Verifique o nome do campo da galeria, o Post ID atual e se este imovel possui fotos cadastradas.</strong>';
		echo '<dl>';
		echo '<dt>Render executado</dt><dd>' . esc_html( $debug['render_executed'] ?? 'sim' ) . '</dd>';
		echo '<dt>Post ID detectado</dt><dd>' . esc_html( (string) ( $debug['post_id'] ?? 0 ) ) . '</dd>';
		echo '<dt>Nome do campo configurado</dt><dd>' . esc_html( $debug['field'] ?? '' ) . '</dd>';
		echo '<dt>Campo detectado na dynamic tag</dt><dd>' . esc_html( $debug['dynamic_field'] ?? '' ) . '</dd>';
		echo '<dt>Meta key encontrada</dt><dd>' . esc_html( $debug['found_meta_key'] ?? '' ) . '</dd>';
		echo '<dt>Post encontrado por fallback</dt><dd>' . esc_html( (string) ( $debug['fallback_post_id'] ?? 0 ) ) . '</dd>';
		echo '<dt>Origem selecionada</dt><dd>' . esc_html( $debug['selected_source'] ?? '' ) . '</dd>';
		echo '<dt>Origem usada</dt><dd>' . esc_html( $debug['used_source'] ?? '' ) . '</dd>';
		echo '<dt>Tipo do dado retornado por get_post_meta</dt><dd>' . esc_html( $debug['meta_type'] ?? '' ) . '</dd>';
		echo '<dt>Quantidade antes da normalizacao</dt><dd>' . esc_html( (string) ( $debug['before_count'] ?? 0 ) ) . '</dd>';
		echo '<dt>Quantidade depois da normalizacao</dt><dd>' . esc_html( (string) ( $debug['after_count'] ?? 0 ) ) . '</dd>';
		echo '<dt>Chaves testadas</dt><dd><pre>' . esc_html( implode( ', ', $debug['candidate_keys'] ?? array() ) ) . '</pre></dd>';
		echo '<dt>Meta keys existentes nesse post</dt><dd><pre>' . esc_html( implode( ', ', $this->limit_debug_keys( $debug['available_keys'] ?? array() ) ) ) . '</pre></dd>';
		echo '<dt>Amostra segura do formato retornado</dt><dd><pre>' . esc_html( $debug['sample'] ?? '' ) . '</pre></dd>';
		echo '</dl>';
		echo '</div>';
	}

	private function limit_debug_keys( $keys ) {
		if ( ! is_array( $keys ) ) {
			return array();
		}

		$keys = array_values( array_unique( array_map( 'strval', $keys ) ) );
		sort( $keys );

		if ( count( $keys ) > 80 ) {
			$keys = array_slice( $keys, 0, 80 );
			$keys[] = '...';
		}

		return $keys;
	}

	private function safe_debug_sample( $data ) {
		if ( is_object( $data ) ) {
			$data = get_object_vars( $data );
		}

		$sample = wp_json_encode( $data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
		if ( false === $sample ) {
			$sample = $this->data_type( $data );
		}

		if ( strlen( $sample ) > 1200 ) {
			$sample = substr( $sample, 0, 1200 ) . '...';
		}

		return $sample;
	}

	private function data_type( $data ) {
		if ( is_object( $data ) ) {
			return 'object:' . get_class( $data );
		}

		if ( is_array( $data ) ) {
			return 'array(' . count( $data ) . ')';
		}

		return gettype( $data );
	}

	private function get_watermark_url( $settings, $raw_settings = array() ) {
		$source      = $settings['watermark_source'] ?? 'manual';
		$post_id     = $this->detect_current_post_id( $settings );
		$dynamic_key = $this->extract_dynamic_image_field( $raw_settings, 'watermark_image' );
		$meta_key    = isset( $settings['watermark_meta_key'] ) ? trim( (string) $settings['watermark_meta_key'] ) : '';

		if ( 'manual' === $source && ! empty( $settings['watermark_image'] ) && is_array( $settings['watermark_image'] ) ) {
			$image = $this->normalize_image_candidate( $settings['watermark_image'], $post_id );
			if ( ! empty( $image['full'] ) ) {
				return $image['full'];
			}
		}

		if ( '' === $meta_key && '' !== $dynamic_key ) {
			$meta_key = $dynamic_key;
		}

		if ( '' === $meta_key ) {
			return '';
		}

		$lookup = $this->get_meta_lookup( $post_id, $meta_key );
		$image  = $this->first_image_from_value( $lookup['value'] ?? null, $post_id );

		if ( $image ) {
			return $image['full'];
		}

		$fallback_lookup = $this->find_post_with_meta_gallery( $meta_key, $post_id );
		if ( ! empty( $fallback_lookup['post_id'] ) ) {
			$image = $this->first_image_from_value( $fallback_lookup['value'], absint( $fallback_lookup['post_id'] ) );
			if ( $image ) {
				return $image['full'];
			}
		}

		return '';
	}

	private function first_image_from_value( $value, $post_id ) {
		$candidates = $this->extract_image_candidates( $value );
		$images     = $this->normalize_images( $candidates, $post_id );

		return $images[0] ?? null;
	}

	private function render_attrs( $attrs ) {
		$output = array();
		foreach ( $attrs as $key => $value ) {
			if ( '' === $value || null === $value ) {
				continue;
			}
			$output[] = sanitize_key( $key ) . '="' . esc_attr( $value ) . '"';
		}

		return implode( ' ', $output );
	}

	private function slider_css_value( $settings, $key, $fallback ) {
		if ( empty( $settings[ $key ]['size'] ) && '0' !== (string) ( $settings[ $key ]['size'] ?? '' ) ) {
			return $fallback;
		}

		$unit = $settings[ $key ]['unit'] ?? 'px';
		return $settings[ $key ]['size'] . $unit;
	}

	private function slider_size( $settings, $key, $fallback ) {
		if ( isset( $settings[ $key ]['size'] ) && '' !== $settings[ $key ]['size'] ) {
			return (string) $settings[ $key ]['size'];
		}

		return $fallback;
	}

	private function get_image_size_options() {
		$options = array(
			'thumbnail' => 'Thumbnail',
			'medium'    => 'Medium',
			'large'     => 'Large',
			'full'      => 'Full',
		);

		foreach ( get_intermediate_image_sizes() as $size ) {
			if ( ! isset( $options[ $size ] ) ) {
				$options[ $size ] = ucfirst( str_replace( array( '-', '_' ), ' ', $size ) );
			}
		}

		return $options;
	}
}
