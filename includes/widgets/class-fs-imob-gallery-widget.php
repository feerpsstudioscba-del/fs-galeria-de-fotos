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
				'label' => 'Fotos',
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'gallery',
			array(
				'label'       => 'Galeria',
				'type'        => Controls_Manager::GALLERY,
				'description' => 'Use imagens manuais ou uma tag dinamica do Elementor/JetEngine.',
				'dynamic'     => array( 'active' => true ),
			)
		);

		$this->add_control(
			'jet_meta_key',
			array(
				'label'       => 'Campo meta do JetEngine',
				'type'        => Controls_Manager::TEXT,
				'description' => 'Opcional. Informe a chave do campo quando as imagens estiverem salvas no post atual.',
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
			'watermark_image',
			array(
				'label'     => "Imagem da marca d'agua",
				'type'      => Controls_Manager::MEDIA,
				'dynamic'   => array( 'active' => true ),
				'condition' => array( 'watermark_enabled' => 'yes' ),
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
				'default'   => 'rgba(0,0,0,0.18)',
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
				'label'      => "Largura da marca d'agua",
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
		$images   = $this->get_gallery_images( $settings );

		if ( empty( $images ) ) {
			if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
				echo '<div class="fs-imob-gallery__notice">Selecione imagens para a galeria.</div>';
			}
			return;
		}

		$watermark_url = $this->get_watermark_url( $settings );
		$watermark_on  = 'yes' === ( $settings['watermark_enabled'] ?? '' ) && ! empty( $watermark_url );

		if ( 'yes' === ( $settings['watermark_enabled'] ?? '' ) && empty( $watermark_url ) ) {
			if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
				echo '<div class="fs-imob-gallery__notice">Selecione uma imagem de marca d\'agua.</div>';
			}
		}

		$position       = $settings['main_position'] ?? 'left';
		$mobile_order   = $settings['mobile_order'] ?? 'main_first';
		$mobile_columns = $settings['mobile_thumb_columns'] ?? '2';
		$lightbox       = 'yes' === ( $settings['enable_lightbox'] ?? '' );
		$display        = $settings['watermark_display'] ?? 'mosaic';
		$root_classes   = array(
			'fs-imob-gallery',
			'fs-imob-gallery--main-' . sanitize_html_class( $position ),
			'fs-imob-gallery--mobile-' . sanitize_html_class( $mobile_order ),
			'fs-imob-gallery--mobile-cols-' . sanitize_html_class( $mobile_columns ),
		);

		if ( $watermark_on ) {
			$root_classes[] = 'fs-imob-gallery--watermark-enabled';
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
		echo '<div class="fs-imob-gallery__mosaic">';
		$this->render_item( $images[0], 0, 'main', $settings, $watermark_on, $watermark_url, $lightbox );
		echo '<div class="fs-imob-gallery__thumbs">';

		$total      = count( $images );
		$thumbs     = array_slice( $images, 1, 4 );
		$last_index = count( $thumbs ) - 1;
		foreach ( $thumbs as $i => $image ) {
			$remaining = ( $i === $last_index && $total > 5 ) ? $total - 5 : 0;
			$this->render_item( $image, $i + 1, 'thumb', $settings, $watermark_on, $watermark_url, $lightbox, $remaining );
		}

		echo '</div>';
		echo '</div>';
		echo '<script type="application/json" class="fs-imob-gallery__data">' . wp_json_encode( $images, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT ) . '</script>';
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
		echo '<img class="fs-imob-gallery__image" src="' . esc_url( $image['url'] ) . '" alt="' . esc_attr( $image['alt'] ) . '" loading="' . ( 0 === $index ? 'eager' : 'lazy' ) . '">';

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
		return 'all' === $apply || ( 'main' === $apply && 'main' === $type ) || ( 'thumbs' === $apply && 'thumb' === $type );
	}

	private function get_gallery_images( $settings ) {
		$images = array();

		if ( ! empty( $settings['gallery'] ) && is_array( $settings['gallery'] ) ) {
			foreach ( $settings['gallery'] as $item ) {
				$images[] = $this->normalize_image( $item, $settings );
			}
		}

		if ( empty( $images ) && ! empty( $settings['jet_meta_key'] ) ) {
			$meta = get_post_meta( get_the_ID(), sanitize_key( $settings['jet_meta_key'] ), true );
			$images = $this->images_from_meta( $meta, $settings );
		}

		return array_values( array_filter( $images ) );
	}

	private function images_from_meta( $meta, $settings ) {
		if ( empty( $meta ) ) {
			return array();
		}

		if ( is_string( $meta ) ) {
			$decoded = json_decode( $meta, true );
			if ( json_last_error() === JSON_ERROR_NONE ) {
				$meta = $decoded;
			} else {
				$meta = array_map( 'trim', explode( ',', $meta ) );
			}
		}

		if ( ! is_array( $meta ) ) {
			$meta = array( $meta );
		}

		$images = array();
		foreach ( $meta as $item ) {
			if ( is_array( $item ) && isset( $item['id'] ) ) {
				$images[] = $this->normalize_image( array( 'id' => $item['id'] ), $settings );
			} elseif ( is_array( $item ) && isset( $item['url'] ) ) {
				$images[] = $this->normalize_image( array( 'url' => $item['url'] ), $settings );
			} elseif ( is_numeric( $item ) ) {
				$images[] = $this->normalize_image( array( 'id' => (int) $item ), $settings );
			} elseif ( is_string( $item ) && filter_var( $item, FILTER_VALIDATE_URL ) ) {
				$images[] = $this->normalize_image( array( 'url' => $item ), $settings );
			}
		}

		return $images;
	}

	private function normalize_image( $item, $settings ) {
		$id   = ! empty( $item['id'] ) ? absint( $item['id'] ) : 0;
		$size = $settings['image_size'] ?? 'large';
		$url  = '';
		$full = '';
		$alt  = '';

		if ( $id ) {
			$src  = wp_get_attachment_image_src( $id, $size );
			$big  = wp_get_attachment_image_src( $id, 'full' );
			$url  = $src ? $src[0] : '';
			$full = $big ? $big[0] : $url;
			$alt  = get_post_meta( $id, '_wp_attachment_image_alt', true );
		} elseif ( ! empty( $item['url'] ) ) {
			$url  = esc_url_raw( $item['url'] );
			$full = $url;
		}

		if ( empty( $url ) ) {
			return null;
		}

		return array(
			'id'   => $id,
			'url'  => $url,
			'full' => $full,
			'alt'  => $alt,
		);
	}

	private function get_watermark_url( $settings ) {
		if ( empty( $settings['watermark_image'] ) || ! is_array( $settings['watermark_image'] ) ) {
			return '';
		}

		if ( ! empty( $settings['watermark_image']['url'] ) ) {
			return esc_url_raw( $settings['watermark_image']['url'] );
		}

		if ( ! empty( $settings['watermark_image']['id'] ) ) {
			return wp_get_attachment_url( absint( $settings['watermark_image']['id'] ) );
		}

		return '';
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
