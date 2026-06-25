<!-- faq-section.php -->
<style>
  /* Reset wrapper margins */
  .faq-wrap {
    width: 100%;
    max-width: 980px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    gap: 28px;
    align-items: flex-start;
    padding: 0;
  }

  .faq-main {
    flex: 1;
    background: #fff;
    padding: 0;
    border-radius: 12px;
    box-shadow: 0 6px 18px rgba(16, 24, 40, 0.06);
  }

  .faq-header {
    display: flex;
    flex-direction: column;
    gap: 6px;
    margin-bottom: 18px;
  }

  .faq-title {
    font-size: 20px;
    margin: 0;
  }

  .faq-sub {
    color: #6b7280;
    font-size: 14px;
    margin: 0;
  }

  .faq-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
  }

  .faq-item {
    border-radius: 10px;
    overflow: hidden;
    border: 1px solid #eef2ff;
    background: linear-gradient(180deg, #fff, #fbfdff);
  }

  .faq-question {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px;
    cursor: pointer;
    width: 100%;
    border: none;
    background: transparent;
    text-align: left;
    font-size: 16px;
    font-weight: 600;
    color: #0b1220;
    transition: background .15s, color .15s;
  }

  .faq-q-text {
    flex: 1;
  }

  .faq-meta {
    display: flex;
    gap: 10px;
    align-items: center;
    color: #6b7280;
    font-size: 13px;
  }

  .faq-toggle {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    border: none;
    background: #f1f5f9;
    display: inline-grid;
    place-items: center;
    transition: transform .18s, background .18s;
  }

  .faq-item[aria-expanded="true"] .faq-toggle {
    transform: rotate(180deg);
    background: #222;
    color: #fff;
  }

  .faq-answer {
    max-height: 0;
    overflow: hidden;
    padding: 0 16px;
    /* no top/bottom padding when closed */
    transition: max-height .28s cubic-bezier(.2, .9, .3, 1), padding .18s;
    background: linear-gradient(180deg, #fbfdff, #ffffff);
  }

  .faq-item[aria-expanded="true"] .faq-answer {
    padding: 12px 16px 16px;
    /* restored when open */
    max-height: 400px;
  }

  .faq-answer p {
    margin: 0;
    padding: 20px;
    color: #263238;
    line-height: 1.5;
    font-size: 14px;
  }

  @media (max-width:460px) {
    .faq-question {
      padding: 12px;
      font-size: 15px;
    }

    .faq-toggle {
      width: 32px;
      height: 32px;
    }

    .faq-answer {
      font-size: 14px;
    }
  }
</style>

<div class="faq-wrap" aria-label="Frequently Asked Questions">
  <section class="faq-main">
    <div class="faq-header">
      <h2 class="faq-title">Frequently Asked Questions</h2>
      <p class="faq-sub"> Quick answers to common questions — easy and mobile friendly.</p>
    </div>

    <div class="faq-list" id="faqList">
      <article class="faq-item" aria-expanded="false">
        <button class="faq-question" type="button">
          <span class="faq-q-text">How long does shipping usually take?</span>
          <span class="faq-meta">Usually 3–7 days</span>
          <span class="faq-toggle" aria-hidden="true">▾</span>
        </button>
        <div class="faq-answer" role="region" aria-hidden="true">
          <p>Standard shipping time is <strong>3–7 business days</strong> depending on your location. Expedited options
            are available.</p>
        </div>
      </article>

      <article class="faq-item" aria-expanded="false">
        <button class="faq-question" type="button">
          <span class="faq-q-text">Can I return or exchange an item?</span>
          <span class="faq-meta">14-day returns</span>
          <span class="faq-toggle" aria-hidden="true">▾</span>
        </button>
        <div class="faq-answer" role="region" aria-hidden="true">
          <p>Yes — returns accepted within <strong>14 days</strong> of delivery. Must be unused and in original
            packaging.</p>
        </div>
      </article>

      <article class="faq-item" aria-expanded="false">
        <button class="faq-question" type="button">
          <span class="faq-q-text">Can I return or exchange an item?</span>
          <span class="faq-meta">14-day returns</span>
          <span class="faq-toggle" aria-hidden="true">▾</span>
        </button>
        <div class="faq-answer" role="region" aria-hidden="true">
          <p>Yes — returns accepted within <strong>14 days</strong> of delivery. Must be unused and in original
            packaging.</p>
        </div>
      </article>
    </div>
  </section>
</div>

<script>
  (function () {
    const faqList = document.getElementById('faqList');
    const items = Array.from(faqList.querySelectorAll('.faq-item'));
    items.forEach(item => {
      const btn = item.querySelector('.faq-question');
      btn.addEventListener('click', () => {
        const isOpen = item.getAttribute('aria-expanded') === 'true';
        item.setAttribute('aria-expanded', !isOpen);
        const answer = item.querySelector('.faq-answer');
        answer.style.maxHeight = !isOpen ? answer.scrollHeight + "px" : "0";
        answer.style.padding = !isOpen ? "12px 16px 16px" : "0 16px";
      });
    });
  })();
</script>