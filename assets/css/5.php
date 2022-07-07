<style>
	.hdsmi-template--5 {
		--hdsmi--title--font-size: 5vw;
		--hdsmi--location--font-size: 3vw;
		--hdsmi--logo--height: 3vw
	}

	.hdsmi-template--5 .hdsmi-template__text {
		width: 50vw;
		position: absolute;
		top: 50%;
		left: calc(50% - 4vw);
		transform: translateY(-50%)
	}

	.hdsmi-template--5 .hdsmi-template__text>* {
		display: inline;
		padding: 1vw 2vw;
		background-color: var(--hdsmi--text--background-color);
		-webkit-box-decoration-break: clone;
		box-decoration-break: clone
	}

	.hdsmi-template--5 .hdsmi-template__text>:after {
		content: "\A";
		white-space: pre-line
	}

	.hdsmi-template--5 .hdsmi-template__title {
		line-height: 1
	}

	.hdsmi-template--5 .hdsmi-template__image {
		width: 50vw;
		height: 100%;
		object-fit: cover
	}

	.hdsmi-template--5 .hdsmi-template__logo {
		position: absolute;
		top: 3vw;
		right: 3vw
	}
</style>