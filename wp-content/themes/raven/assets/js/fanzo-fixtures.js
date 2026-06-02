/**
 * Fanzo Fixtures Widget
 * Handles sport filtering and paginated load-more.
 * Reads data injected by the PHP widget via window.ravenFanzoData[widgetId].
 */
(function () {
  "use strict";

  // ── Helpers ────────────────────────────────────────────────────────────────

  function ordinal(n) {
    const v = n % 100;
    if (v >= 11 && v <= 13) return "th";
    return ["th", "st", "nd", "rd"][n % 10] || "th";
  }

  function escHtml(str) {
    return String(str || "")
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;");
  }

  // Parse the local ISO string directly to avoid client-timezone mismatch.
  // "2026-06-11T20:00:00+01:00" → { dateKey, year, month, day, time }
  function parseLocal(iso) {
    const [datePart, timePart = ""] = (iso || "").split("T");
    const [year, month, day] = datePart.split("-").map(Number);
    return { dateKey: datePart, year, month, day, time: timePart.slice(0, 5) };
  }

  function formatDateLabel(year, month, day) {
    const d = new Date(year, month - 1, day);
    const dayName = d.toLocaleDateString("en-GB", { weekday: "short" });
    const monthName = d.toLocaleDateString("en-GB", { month: "long" });
    return `${dayName} ${day}${ordinal(day)} ${monthName}`;
  }

  // ── Templates ──────────────────────────────────────────────────────────────

  function renderRow(fixture, bookLabel, bookFallbackUrl) {
    const { time } = parseLocal(fixture.startTimeLocal);
    const home = fixture.teams?.find((t) => t.side === "home") || {};
    const away = fixture.teams?.find((t) => t.side === "away") || {};

    const teamLogoClass =
      "hidden sm:block w-10 h-10 lg:w-18 lg:h-18 shrink-0 object-contain";
    const homeLogo = home.logo
      ? `<img src="${escHtml(home.logo)}" alt="${escHtml(home.name || "")}" class="${teamLogoClass}" loading="lazy" width="76" height="76">`
      : "";
    const awayLogo = away.logo
      ? `<img src="${escHtml(away.logo)}" alt="${escHtml(away.name || "")}" class="${teamLogoClass}" loading="lazy" width="76" height="76">`
      : "";

    const sportLogo = fixture.sport?.logo
      ? `<img src="${escHtml(fixture.sport.logo)}" alt="${escHtml(fixture.sport.name || "")}" class="w-6 h-6 shrink-0 object-contain" loading="lazy" width="24" height="24">`
      : "";
    const competitionLogo = fixture.competition?.logo
      ? `<span class="hidden sm:flex w-12 h-12 items-center justify-center shrink-0 bg-white rounded-[3px]"><img src="${escHtml(fixture.competition.logo)}" alt="${escHtml(fixture.competition.name || "")}" class="w-10 h-auto shrink-0 object-contain" loading="lazy" width="40" height="40"></span>`
      : "";

    const hasTeamLogos = !!(home.logo || away.logo);

    const bookTxt = bookLabel || "Book";
    const bookHref = fixture.bookingLink || bookFallbackUrl;
    const bookTarget = fixture.bookingLink
      ? ' target="_blank" rel="noopener noreferrer"'
      : "";
    const bookBtn = `<a href="${escHtml(bookHref)}"${bookTarget} class="btn btn-primary btn-sm text-subtitle-sm shrink-0">${escHtml(bookTxt)}</a>`;

    const middle = hasTeamLogos
      ? `<div class="flex items-center justify-center gap-4 flex-1">
    ${homeLogo}
    <div class="text-center">
      <p class="text-body-xl font-bold leading-[1.3] text-white">${escHtml(fixture.eventName)}</p>
      <div class="flex items-center justify-center gap-1.5">
        <p class="text-body-lg font-light text-cloud">${escHtml(fixture.competition?.name || "")}</p>
      </div>
    </div>
    ${awayLogo}
  </div>`
      : `<div class="flex items-center gap-4 flex-1 justify-center">
    ${competitionLogo}
    <div class="text-left flex flex-col gap-1">
      <p class="text-body-xl font-bold leading-[1.3] text-white">${escHtml(fixture.eventName)}</p>
      <p class="text-body-lg font-light text-cloud">${escHtml(fixture.competition?.name || "")}</p>
    </div>
  </div>`;

    return `<div class="fanzo-fixture-row flex items-center justify-between gap-4 md:gap-6 px-4 py-3 border-b-2 border-l-2 border-r-2 border-ship">
  <div class="flex items-center gap-2 shrink-0">
    <time class="text-subtitle-xl text-birch text-left">${escHtml(time)}</time>
  </div>
  ${middle}
  ${bookBtn}
</div>`;
  }

  function renderGroup(label, rows) {
    return `<div class="fanzo-fixture-group">
  <div class="bg-ship rounded-t-[7px] px-4 py-4">
    <p class="text-heading-2xs text-birch">${escHtml(label)}</p>
  </div>
  ${rows.join("")}
</div>`;
  }

  function renderList(fixtures, bookLabel, bookFallbackUrl, noFixturesText) {
    if (!fixtures.length) {
      return `<p class="text-cloud py-6 text-center text-body-lg">${escHtml(noFixturesText)}</p>`;
    }

    const groups = new Map();
    fixtures.forEach((f) => {
      const { dateKey, year, month, day } = parseLocal(f.startTimeLocal);
      if (!groups.has(dateKey)) {
        groups.set(dateKey, {
          label: formatDateLabel(year, month, day),
          rows: [],
        });
      }
      groups.get(dateKey).rows.push(f);
    });

    let html = "";
    groups.forEach(({ label, rows }) => {
      html += renderGroup(
        label,
        rows.map((f) => renderRow(f, bookLabel, bookFallbackUrl)),
      );
    });
    return html;
  }

  // ── Widget class ───────────────────────────────────────────────────────────

  class FanzoFixtures {
    constructor(el, data) {
      this.el = el;
      this.fixtures = data.fixtures || [];
      this.perPage = data.perPage ?? 10;
      this.bookLabel = data.bookLabel || "Book";
      this.bookFallbackUrl = data.bookFallbackUrl || "/book-now";
      this.noFixturesText = data.noFixturesText || "No fixtures found.";

      this.listEl = el.querySelector(".js-fanzo-list");
      this.filterEl = el.querySelector(".js-fanzo-filter");
      this.filterWrapEl = el.querySelector(".js-fanzo-filter-wrap");
      this.loadMoreWrapEl = el.querySelector(".js-fanzo-load-more-wrap");
      this.loadMoreEl = el.querySelector(".js-fanzo-load-more");

      this.activeSport = "";
      this.page = 1;

      this.buildFilter();
      this.paint();
      this.bindEvents();
    }

    buildFilter() {
      const sports = new Map();
      this.fixtures.forEach((f) => {
        if (f.sport?.id) sports.set(String(f.sport.id), f.sport.name);
      });

      // Hide the filter wrapper if there is only one (or zero) sport.
      if (sports.size <= 1) {
        this.filterWrapEl?.classList.add("hidden");
        return;
      }

      const opts = [...sports.entries()]
        .map(([id, name]) => `<option value="${id}">${escHtml(name)}</option>`)
        .join("");

      this.filterEl?.insertAdjacentHTML("beforeend", opts);
    }

    filtered() {
      if (!this.activeSport) return this.fixtures;
      return this.fixtures.filter(
        (f) => String(f.sport?.id) === this.activeSport,
      );
    }

    paint() {
      const all = this.filtered();
      const showAll = this.perPage === 0;
      const shown = showAll ? all.length : this.page * this.perPage;

      this.listEl.innerHTML = renderList(
        all.slice(0, shown),
        this.bookLabel,
        this.bookFallbackUrl,
        this.noFixturesText,
      );

      if (this.loadMoreWrapEl) {
        this.loadMoreWrapEl.hidden = showAll || shown >= all.length;
      }
    }

    bindEvents() {
      this.filterEl?.addEventListener("change", () => {
        this.activeSport = this.filterEl.value;
        this.page = 1;
        this.paint();
      });

      this.loadMoreEl?.addEventListener("click", () => {
        this.page++;
        this.paint();
      });
    }
  }

  // ── Boot ───────────────────────────────────────────────────────────────────

  function init() {
    document.querySelectorAll(".js-fanzo-fixtures").forEach((el) => {
      const id = el.dataset.widgetId;
      const data = window.ravenFanzoData?.[id];
      if (data) new FanzoFixtures(el, data);
    });
  }

  // Works whether the script is deferred or in-footer.
  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", init);
  } else {
    init();
  }
})();
