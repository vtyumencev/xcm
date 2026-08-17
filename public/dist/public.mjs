//#region node_modules/nanoevents/index.js
var e = () => ({
	emit(e, ...t) {
		for (let n = this.events[e] || [], r = 0, i = n.length; r < i; r++) n[r](...t);
	},
	events: {},
	on(e, t) {
		return (this.events[e] ||= []).push(t), () => {
			this.events[e] = this.events[e]?.filter((e) => t !== e);
		};
	}
}), t = Object.defineProperty, n = Object.getOwnPropertySymbols, r = Object.prototype.hasOwnProperty, i = Object.prototype.propertyIsEnumerable, a = (e, n, r) => n in e ? t(e, n, {
	enumerable: !0,
	configurable: !0,
	writable: !0,
	value: r
}) : e[n] = r, o = (e, t) => {
	for (var o in t ||= {}) r.call(t, o) && a(e, o, t[o]);
	if (n) for (var o of n(t)) i.call(t, o) && a(e, o, t[o]);
	return e;
}, s = (e, t, n) => new Promise((r, i) => {
	var a = (e) => {
		try {
			s(n.next(e));
		} catch (e) {
			i(e);
		}
	}, o = (e) => {
		try {
			s(n.throw(e));
		} catch (e) {
			i(e);
		}
	}, s = (e) => e.done ? r(e.value) : Promise.resolve(e.value).then(a, o);
	s((n = n.apply(e, t)).next());
}), c = new class {
	constructor() {
		this.isInitialized = !1, this.BASE_Z_INDEX = 100, this.TAB_QUERY_SELECTORS = "a[href]:not([disabled]),button:not([disabled]),textarea:not([disabled]),input[type=\"submit\"]:not([disabled]),input[type=\"text\"]:not([disabled]),input[type=\"radio\"]:not([disabled]),input[type=\"checkbox\"]:not([disabled]),select:not([disabled])", this.modalsEnabled = !1, this.hookOptionsList = [], this.connectedModules = [], this.modals = [], this.emitter = e(), document.addEventListener("DOMContentLoaded", this.updateScrollbarBuffer), window.addEventListener("resize", this.updateScrollbarBuffer);
	}
	on(e, t) {
		return this.emitter.on(e, t);
	}
	updateScrollbarBuffer() {
		let e = document.createElement("div");
		e.style.visibility = "hidden", e.style.overflow = "scroll", document.body.appendChild(e);
		let t = document.createElement("div");
		e.appendChild(t);
		let n = e.offsetWidth - t.offsetWidth;
		e.parentNode.removeChild(e), document.body.style.setProperty("--scroll-bar-buffer", n + "px");
	}
	openModal(e) {
		return s(this, arguments, function* (e, t = {}) {
			for (let t of this.modals) if (t.el === e) return;
			let n = o({}, {
				closable: !0,
				onOpening: null,
				onOpen: null,
				onClosing: null,
				onClose: null,
				transitionDuration: 300
			});
			for (let t of this.hookOptionsList) t.modalId === e.getAttribute("id") && (n = o(o({}, n), t.options));
			if (n = o(o({}, n), t), e.style.zIndex = (this.BASE_Z_INDEX + this.modals.length).toString(), e.setAttribute("tabindex", "-1"), e.classList.add("showing"), e.classList.add("shown"), n.onOpening) {
				let t = !1;
				setTimeout(() => {
					t || e.classList.add("loading");
				}, 20), yield n.onOpening(e).catch(() => {
					throw e.classList.remove("loading"), e.classList.remove("shown"), e.classList.remove("showing"), Error("Impossible to create a modal window with an opening callback: " + n.onOpening);
				}), t = !0, e.classList.remove("loading");
			}
			e.querySelector(".sx-modal-dialog").clientHeight > window.innerHeight ? e.classList.add("scrollable") : e.classList.remove("scrollable"), e.offsetWidth, e.classList.add("visible"), setTimeout(() => {
				var t;
				e.classList.add("released"), e.classList.remove("showing"), (t = e.querySelectorAll(this.TAB_QUERY_SELECTORS)[0]) == null || t.focus();
			}, n.transitionDuration + 100);
			let r = [];
			e.querySelectorAll(".js-action-close").forEach((e) => {
				r.push({
					el: e,
					name: "click",
					callback: this.destroyModalClickEvent.bind(this)
				});
			}), r.push({
				el: e,
				name: "click",
				callback: this.onDocumentClick.bind(this)
			}), r.push({
				el: e,
				name: "keydown",
				callback: this.onKeyEvent.bind(this)
			});
			for (let e of r) e.el.addEventListener(e.name, e.callback);
			this.modals.push({
				previousActiveElement: document.activeElement,
				el: e,
				options: n,
				events: r
			}), this.updateModals();
		});
	}
	destroyModal(e) {
		let t = {
			modal: e,
			callback: () => {
				let t = e.el;
				t.classList.add("hiding"), t.classList.remove("visible"), t.classList.remove("released"), setTimeout(() => {
					var n;
					t.classList.remove("shown"), t.classList.remove("hiding"), (n = e.previousActiveElement) == null || n.focus(), this.updateModals();
				}, e.options.transitionDuration);
				for (let t of e.events) t.el.removeEventListener(t.name, t.callback);
				e.options.onClose && e.options.onClose(t), this.modals = this.modals.filter((e) => t !== e.el);
			}
		};
		this.emitter.emit("dialogDestroying", t), t.callback();
	}
	onDocumentClick(e) {
		e.target.closest(".js-sx-modal-dialog-body") || this.modals[this.modals.length - 1].options.closable === !0 && this.destroyModal(this.modals[this.modals.length - 1]);
	}
	onKeyEvent(e) {
		if (e.key === "Escape" && this.modals[this.modals.length - 1].options.closable === !0) {
			this.destroyModal(this.modals[this.modals.length - 1]);
			return;
		}
		let t = this.modals[this.modals.length - 1].el.querySelectorAll(this.TAB_QUERY_SELECTORS), n = t[0], r = t[t.length - 1];
		e.key === "Tab" && (this.modals[this.modals.length - 1].el.contains(document.activeElement) ? e.shiftKey ? document.activeElement === n && (r.focus(), e.preventDefault()) : document.activeElement === r && (n.focus(), e.preventDefault()) : (n && n.focus(), e.preventDefault()));
	}
	destroyModalClickEvent(e) {
		let t = e.target.closest(".sx-modal"), n = this.modals.find((e) => t === e.el);
		n && this.destroyModal(n);
	}
	updateModals() {
		if (this.modals.length > 0) {
			if (this.modalsEnabled) return;
			this.modalsEnabled = !0, document.body.classList.add("has-active-sx-modal");
		} else {
			if (!this.modalsEnabled) return;
			this.modalsEnabled = !1, document.body.classList.remove("has-active-sx-modal");
		}
	}
	start(e) {
		if (this.isInitialized) return console.warn("SXModal: Already initialized"), this;
		this.keyDownEvent = this.onKeyEvent.bind(this);
		for (let t of e ?? []) {
			let e = new t(this);
			e.modalInit(), this.connectedModules.push(e);
		}
		this.isInitialized = !0;
	}
	addOptionsHook(e, t) {
		this.hookOptionsList.push({
			modalId: e,
			options: t
		});
	}
	init(e, t = {}) {
		let n;
		e instanceof HTMLElement ? n = e : typeof e == "string" && (n = document.querySelector(e)), n && this.openModal(n, t);
	}
	destroy(e) {
		let t;
		if (e instanceof HTMLElement ? t = e : typeof e == "string" && (t = document.querySelector(e)), t) for (let e of this.modals) e.el === t && this.destroyModal(e);
	}
}(), l = "js-xcm-manager-category-review", u, d, f, p = (e) => {
	let t = f.getUserConfig();
	for (let n of Object.entries(t.consent)) if (e.consent[n[0]] === !0 && n[1] === !1) return !0;
	return !1;
}, m = (e) => {
	e.target.closest(`.${l}`).classList.contains("active") ? e.target.closest(`.${l}`).classList.remove("active") : e.target.closest(`.${l}`).classList.add("active");
}, h = (e) => {
	let t = e.target.getAttribute("data-vendor-id"), n = y("xcm-modal-vendor");
	document.body.appendChild(n), c.init(n, {
		onOpening: async () => {
			await fetch(window.XCMSettingsPublic.ajaxUrl + "?action=xcm_vendor&vendorId=" + t, {}).then((e) => e.text()).then((e) => {
				n.querySelector(".sx-modal-dialog__content").innerHTML = e;
			});
		},
		onClose: () => {
			setTimeout(() => {
				n.remove();
			}, 500);
		}
	});
}, g = async (e) => {
	e.preventDefault(), e.submitter.getAttribute("data-action") === "accept-all" && e.target.querySelectorAll(".xcm-manager-category-switch input").forEach((e) => {
		e.checked = !0;
	}), setTimeout(() => {
		c.destroy(u);
	}, 100);
	let t = f.getUserConfig();
	await fetch(window.XCMSettingsPublic.ajaxUrl + "?action=xcm_update", {
		method: "POST",
		body: new FormData(e.target)
	}).then((e) => e.json()), (window.XCMSettingsPublic.reloadOnUpdate === !0 || t && p(t)) && window.location.reload(), f.emit("consentUpdated");
}, _ = (e, t = null) => {
	if (e.querySelectorAll(".js-xcm-manager-category-toggle").forEach((e) => {
		e.addEventListener("click", m);
	}), e.querySelectorAll(".js-xcm-vendor-review").forEach((e) => {
		e.addEventListener("click", h);
	}), t) {
		let n = e.querySelector(`.xcm-manager-category[data-id="${t}"]`);
		n && setTimeout(() => {
			n.classList.add("active"), n.scrollIntoView({ behavior: "smooth" });
		}, 500);
	}
	e.querySelector(".js-xcm-manager-form").addEventListener("submit", g);
}, v = (e) => {
	e.querySelectorAll(".js-xcm-manager-category-toggle").forEach((e) => {
		e.removeEventListener("click", m);
	}), e.querySelectorAll(".js-xcm-vendor-review").forEach((e) => {
		e.removeEventListener("click", h);
	}), e.querySelector(".js-xcm-manager-form").removeEventListener("submit", g);
}, y = (e) => {
	let t = document.createElement("div");
	return t.classList.add("sx-modal"), t.classList.add("xcm-modal"), t.setAttribute("id", e), t.innerHTML = "\n            <div class=\"sx-modal__inner\">\n                <div class=\"sx-modal-dialog__bg\"></div>\n                <div class=\"sx-modal-dialog modal-dialog--lg\">\n                    <div class=\"sx-modal-dialog__container\">\n                        <div class=\"sx-modal-dialog__body js-sx-modal-dialog-body\">\n                            <div class=\"sx-modal-dialog__content\"></div>\n                        </div>\n                    </div>\n                </div>\n                <div class=\"sx-modal-loading\">\n                    <div class=\"sx-modal-loading__body\">\n                        Wird geladen...\n                    </div>\n                </div>\n            </div>", document.body.appendChild(t), t;
}, b = (() => {
	let e = async () => await fetch(window.XCMSettingsPublic.ajaxUrl + "?action=xcm_overview", {}).then((e) => e.text()).then((e) => e);
	return {
		show(t = !0, n = null) {
			let r = new Promise((e) => e(!0));
			u || (u = y("xenio-consent-modal"), d = u.querySelector(".sx-modal-dialog__content"), document.body.appendChild(u), r = new Promise(async (t) => {
				d.innerHTML = await e(), t(!0);
			})), c.init(u, {
				onOpening: async () => {
					await r, _(d, n);
				},
				onClose: () => {
					v(d);
				},
				closable: t
			});
		},
		start(e) {
			f = e, window.addEventListener("popstate", () => {
				if (window.location.hash.startsWith("#consent-overview")) {
					let e = window.location.hash.match(/#consent-overview\.cat\.(\d*)/), t = e && (parseInt(e[1]) ?? null);
					this.show(!0, t);
				}
			}), window.addEventListener("DOMContentLoaded", () => {
				!f.getUserConfig() || f.getUserConfig() && f.getUserConfig().content_version !== window.XCMSettingsPublic.contentVersion ? this.show(!1) : f.prolongConfig();
			}, { once: !0 });
		},
		async acceptConsentType(e) {
			await fetch(window.XCMSettingsPublic.ajaxUrl + "?action=xcm_accept_consent_type", {
				method: "POST",
				body: new URLSearchParams({ consent_type: e })
			}).then((e) => e.json()), window.XCMSettingsPublic.reloadOnUpdate === !0 && window.location.reload();
		}
	};
})(), x = (() => {
	let e, t = window.dataLayer = window.dataLayer || [];
	function n(...e) {
		t.push(arguments);
	}
	let r = {
		ad_storage: "denied",
		ad_user_data: "denied",
		ad_personalization: "denied",
		analytics_storage: "denied"
	};
	return {
		start(t) {
			e = t, n("consent", "default", Object.assign(r, e.getConsentConsentsTypes())), e.getUserConfig() && n("event", "update_consent"), e.on("consentUpdated", () => {
				this.update();
			});
		},
		update() {
			n("consent", "update", Object.assign(r, e.getConsentConsentsTypes())), n("event", "update_consent");
		}
	};
})(), S = e(), C = (e, t, n) => {
	let r = /* @__PURE__ */ new Date();
	r.setTime(r.getTime() + n * 24 * 60 * 60 * 1e3);
	let i = "expires=" + r.toUTCString();
	document.cookie = e + "=" + t + ";" + i + ";path=/";
}, w = (e) => {
	let t = e + "=", n = document.cookie.split(";");
	for (let e = 0; e < n.length; e++) {
		let r = n[e];
		for (; r.charAt(0) === " ";) r = r.substring(1);
		if (r.indexOf(t) === 0) return r.substring(t.length, r.length);
	}
	return "";
}, T = (() => {
	let e = window.XCMSettingsPublic;
	return {
		on(e, t) {
			return S.on(e, t);
		},
		emit(e, ...t) {
			return S.emit(e, ...t);
		},
		getUserConfig() {
			let e;
			try {
				let t = w("xcm");
				e = JSON.parse(decodeURIComponent(t));
			} catch {}
			return e;
		},
		prolongConfig() {
			C("xcm", w("xcm"), 365), C("xcm_consent", w("xcm_consent"), 365);
		},
		getConsentConsentsTypes() {
			let t = this.getUserConfig(), n = {};
			if (!t?.consent) return n;
			for (let r of e.categories) {
				if (!t.consent[r.id]) continue;
				let e = r.consent_types?.split(",") ?? [];
				for (let t of e) n[t] = "granted";
			}
			return n;
		},
		isProviderBlocked(t) {
			let n = this.getUserConfig();
			for (let r of e.categories) if (!n?.consent[r.id]) {
				for (let e of r.vendors) if (r.consent_type !== "necessary" && e.provider && t.includes(e.provider)) return {
					provider: e.provider,
					category_id: r.id,
					category_name: r.name
				};
			}
			return !1;
		}
	};
})(), E = () => {
	let e, t = () => {
		document.querySelectorAll(".xcm-embed-replacer").forEach((t) => {
			if (!e.isProviderBlocked(t.getAttribute("data-provider"))) {
				let e = D(t.getAttribute("data-origin"));
				O(e), t.after(e), t.remove();
			}
		});
	};
	return { start(n) {
		e = n, document.addEventListener("DOMContentLoaded", () => {
			t();
		}), e.on("consentUpdated", () => {
			t();
		});
	} };
};
function D(e, t = !0) {
	if (e = t ? e.trim() : e, !e) return null;
	let n = document.createElement("template");
	return n.innerHTML = e, n.content.children[0];
}
function O(e) {
	if (A(e) === !0) e.parentNode.replaceChild(k(e), e);
	else {
		let t = -1, n = e.childNodes;
		for (; ++t < n.length;) O(n[t]);
	}
	return e;
}
function k(e) {
	let t = document.createElement("script");
	t.text = e.innerHTML;
	let n = -1, r = e.attributes, i;
	for (; ++n < r.length;) t.setAttribute((i = r[n]).name, i.value);
	return t;
}
function A(e) {
	return e.tagName === "SCRIPT";
}
var j = E();
c.start(), x.start(T), b.start(T), j.start(T), window.XCM = b;
//#endregion
