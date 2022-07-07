<style>
	:root {
		--hdsmi--text--color: white;
		--hdsmi--text--background-color: rgb(91, 212, 218);
		--hdsmi--background-color: rgb(23, 139, 145);
		--hdsmi--font-family: sans-serif;
		--hdsmi--font-size: 6vw;
		--hdsmi--title--font-size: 6vw;
		--hdsmi--title--font-weight: inherit;
		--hdsmi--title--text-transform: inherit;
		--hdsmi--location--font-size: 4vw;
		--hdsmi--location--font-weight: inherit;
		--hdsmi--location--text-transform: inherit;
		--hdsmi--salary--font-size: 3vw;
		--hdsmi--salary--font-weight: inherit;
		--hdsmi--salary--text-transform: inherit;
		--hdsmi--image--background-blend-mode: none;
		--hdsmi--logo--height: 4vw;
		--hdsmi--logo--width: auto
	}
	body {
		margin: 0;
		padding: 0;
	}
	.hdsmi-template{
		--hdsmi--text--color: <?php echo esc_attr( $args['text_color'] ); ?>;
		--hdsmi--text--background-color: <?php echo esc_attr( $args['bg_text_color'] ); ?>;
		--hdsmi--background-color: <?php echo esc_attr( $args['bg_color'] ); ?>;
	}
	.hdsmi-template {
		width: 100vw;
		aspect-ratio: 120/63;
		display: grid;
		place-items: center;
		background-color: var(--hdsmi--background-color)
	}

	.hdsmi-template__inner {
		position: relative;
		aspect-ratio: 120/63;
		width: 100%
	}

	.hdsmi-template__text {
		font-size: var(--hdsmi--font-size);
		font-family: var(--hdsmi--font-family);
		color: var(--hdsmi--text--color)
	}

	.hdsmi-template__title {
		color: var(--hdsmi--title--color,var(--hdsmi--text--color));
		font-size: var(--hdsmi--title--font-size, var(--hdsmi--font-size));
		font-weight: var(--hdsmi--title--font-weight);
		text-transform: var(--hdsmi--title--text-transform)
	}

	.hdsmi-template__location {
		color: var(--hdsmi--location--color,var(--hdsmi--text--color));
		font-size: var(--hdsmi--location--font-size, var(--hdsmi--font-size));
		font-weight: var(--hdsmi--location--font-weight);
		text-transform: var(--hdsmi--location--text-transform)
	}

	.hdsmi-template__salary {
		color: var(--hdsmi--salary--color,var(--hdsmi--text--color));
		font-size: var(--hdsmi--salary--font-size, var(--hdsmi--font-size));
		font-weight: var(--hdsmi--salary--font-weight);
		text-transform: var(--hdsmi--salary--text-transform)
	}

	.hdsmi-template__logo {
		height: var(--hdsmi--logo--height);
		width: var(--hdsmi--logo--width)
	}
</style>