<style>
	.hdsmi-template--2 {
		--hdsmi--location--font-size: 2.5vw
	}
	.hdsmi-template--2 {
		--hdsmi--location--font-size: 2.5vw
	}

	.hdsmi-template--2 .hdsmi-template__inner {
		display: flex;
		flex-direction: column;
		justify-content: flex-end
	}

	.hdsmi-template--2 .hdsmi-template__image {
		position: absolute;
		z-index: 0;
		top: 0;
		left: 0;
		right: 0;
		bottom: 0;
		width: 100%;
		height: 100%;
		object-fit: cover
	}

	.hdsmi-template--2 .hdsmi-template__text {
		position: relative;
		z-index: 1;
		display: flex;
		flex-direction: column;
		gap: 2vw;
		padding: 0 6vw 4vw
	}

	.hdsmi-template--2 .hdsmi-template__text:after {
		content: '';
		position: absolute;
		z-index: -1;
		top: -10vw;
		left: 0;
		right: 0;
		bottom: 0;
		background: linear-gradient(#00000000,var(--hdsmi--text--background-color) 70%,var(--hdsmi--text--background-color))
	}

	.hdsmi-template--2 .hdsmi-template__title {
		order: 2
	}

	.hdsmi-template--2 .hdsmi-template__location {
		text-transform: uppercase;
		order: 1;
		margin-top: auto
	}

	.hdsmi-template--2 .hdsmi-template__salary {
		order: 3;
		font-weight: 700
	}

	.hdsmi-template--2 .hdsmi-template__logo {
		position: absolute;
		top: 3vw;
		left: 6vw
	}
</style>