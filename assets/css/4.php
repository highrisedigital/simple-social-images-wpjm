<style>
	.hdsmi-template--4 {
		--hdsmi--title--font-size: 4vw;
		--hdsmi--location--font-size: 2.5vw;
		--hdsmi--salary--font-size: 2.5vw;
		--hdsmi--logo--height: 5vw
	}

	.hdsmi-template--4 .hdsmi-template__inner {
		display: grid;
		align-items: end;
		justify-items: center
	}

	.hdsmi-template--4 .hdsmi-template__image {
		width: 100%;
		height: 100%;
		position: absolute;
		z-index: 0;
		top: 0;
		bottom: 0;
		left: 0;
		right: 0;
		object-fit: cover;
		mix-blend-mode: screen
	}

	.hdsmi-template--4 .hdsmi-template__text {
		position: relative;
		z-index: 1;
		background-color: var(--hdsmi--text--background-color);
		padding: 2vw;
		margin: 4vw 15vw;
		text-align: center;
		display: flex;
		flex-direction: column;
		gap: 2vw
	}

	.hdsmi-template--4 .hdsmi-template__logo {
		position: absolute;
		top: 3vw;
		z-index: 0
	}
</style>