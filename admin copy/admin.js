(function () {
  console.log("SOA Admin script loaded");
  console.log("SOA_Settings:", SOA_Settings);

  const restRoot = SOA_Settings.restRoot.replace(/\/$/, "");
  let nonce = SOA_Settings.nonce;
  const listEl = document.getElementById("soa-list");
  const modal = document.getElementById("soa-modal");
  const modalTitle = document.getElementById("soa-modal-title");
  const btnAdd = document.getElementById("soa-add-offer");
  const btnRefresh = document.getElementById("soa-refresh");
  const modalClose = document.getElementById("soa-modal-close");
  const form = document.getElementById("soa-form");
  const mediaBtn = document.getElementById("soa-media-btn");

  console.log("Elements found:", {
    listEl: !!listEl,
    modal: !!modal,
    modalTitle: !!modalTitle,
    btnAdd: !!btnAdd,
    btnRefresh: !!btnRefresh,
    modalClose: !!modalClose,
    form: !!form,
    mediaBtn: !!mediaBtn,
  });

  // inputs
  const idInput = document.getElementById("soa-id");
  const imageInput = document.getElementById("soa-image");
  const titleInput = document.getElementById("soa-title");
  const descInput = document.getElementById("soa-desc");
  const voucherInput = document.getElementById("soa-voucher");
  const buttonTextInput = document.getElementById("soa-button-text");
  const buttonLinkInput = document.getElementById("soa-button-link");

  let wpMediaFrame = null;

  function openModal(type, offer) {
    console.log("Opening modal, type:", type, "offer:", offer);
    modal.style.display = "flex";
    modal.setAttribute("aria-hidden", "false");
    if (type === "add") {
      modalTitle.textContent = "Add Offer";
      form.reset();
      idInput.value = "";
    } else {
      modalTitle.textContent = "Edit Offer";
      idInput.value = offer.id || "";
      imageInput.value = offer.image || "";
      titleInput.value = offer.title || "";
      descInput.value = offer.description || "";
      voucherInput.value = offer.voucher || "";
      buttonTextInput.value = offer.buttonText || "";
      buttonLinkInput.value = offer.buttonLink || "";
    }
  }

  function closeModal() {
    modal.style.display = "none";
    modal.setAttribute("aria-hidden", "true");
  }

  // fetch current offers and re-render list
  async function fetchOffers() {
    try {
      console.log("Fetching offers from:", restRoot + "/offers/v1/list");
      const res = await fetch(restRoot + "/offers/v1/list");
      console.log("Fetch response status:", res.status);
      const data = await res.json();
      console.log("Fetched offers data:", data);
      renderList(data);
    } catch (e) {
      console.error("Error fetching offers:", e);
      listEl.innerHTML =
        '<div class="soa-error">Error fetching offers: ' + e.message + "</div>";
    }
  }

  function renderList(offers) {
    if (!offers || offers.length === 0) {
      listEl.innerHTML =
        '<div class="soa-empty">No offers yet. Click <strong>+ Add Offer</strong> to create one.</div>';
      return;
    }
    listEl.innerHTML = "";
    offers.forEach((o) => {
      const card = document.createElement("div");
      card.className = "soa-card";
      card.dataset.id = o.id || "";

      const left = document.createElement("div");
      left.className = "soa-card-left";
      const img = document.createElement(o.image ? "img" : "div");
      if (o.image) {
        img.src = o.image;
        img.className = "soa-thumb";
      } else {
        img.className = "soa-thumb empty";
        img.textContent = "No image";
      }
      left.appendChild(img);

      const body = document.createElement("div");
      body.className = "soa-card-body";
      const h3 = document.createElement("h3");
      h3.textContent = o.title || "";
      const pdesc = document.createElement("p");
      pdesc.className = "soa-desc";
      pdesc.textContent = o.description || "";
      const pv = document.createElement("p");
      pv.className = "soa-voucher";
      pv.textContent = o.voucher || "";
      const pcta = document.createElement("p");
      pcta.className = "soa-cta";
      const a = document.createElement("a");
      a.href = o.buttonLink || "#";
      a.textContent = o.buttonText || "";
      pcta.appendChild(a);

      body.appendChild(h3);
      body.appendChild(pdesc);
      body.appendChild(pv);
      body.appendChild(pcta);

      const actions = document.createElement("div");
      actions.className = "soa-card-actions";
      const edit = document.createElement("button");
      edit.className = "button soa-edit";
      edit.textContent = "✏️ Edit";
      const del = document.createElement("button");
      del.className = "button soa-delete";
      del.textContent = "🗑️ Delete";
      actions.appendChild(edit);
      actions.appendChild(del);

      card.appendChild(left);
      card.appendChild(body);
      card.appendChild(actions);
      listEl.appendChild(card);

      // events
      edit.addEventListener("click", async () => {
        openModal("edit", o);
      });
      del.addEventListener("click", async () => {
        if (!confirm("Delete this offer?")) return;
        await deleteOffer(o.id);
      });
    });
  }

  async function saveOffersArray(array) {
    console.log("Saving offers array:", array);
    const res = await fetch(restRoot + "/offers/v1/save", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-WP-Nonce": nonce,
      },
      body: JSON.stringify({ offers: array }),
    });
    const data = await res.json();
    console.log("Save response:", data);

    // If nonce is invalid, try to get a fresh one and retry
    if (data.code === "rest_cookie_invalid_nonce") {
      console.log("Nonce invalid, refreshing...");
      const refreshRes = await fetch(restRoot + "/offers/v1/refresh-nonce", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
      });
      if (refreshRes.ok) {
        const refreshData = await refreshRes.json();
        console.log("New nonce received:", refreshData.nonce);
        // Update the nonce and retry
        nonce = refreshData.nonce; // Update the global nonce variable
        const retryRes = await fetch(restRoot + "/offers/v1/save", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            "X-WP-Nonce": nonce,
          },
          body: JSON.stringify({ offers: array }),
        });
        const retryData = await retryRes.json();
        console.log("Retry save response:", retryData);
        if (!retryRes.ok)
          throw new Error(
            retryData.message || "Save failed after nonce refresh"
          );
        return retryData;
      }
    }

    if (!res.ok) throw new Error(data.message || "Save failed");
    return data;
  }

  async function deleteOffer(id) {
    const res = await fetch(restRoot + "/offers/v1/delete", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-WP-Nonce": nonce,
      },
      body: JSON.stringify({ id }),
    });

    if (!res.ok) {
      const err = await res.json();

      // If nonce is invalid, try to get a fresh one and retry
      if (err.code === "rest_cookie_invalid_nonce") {
        console.log("Nonce invalid for delete, refreshing...");
        const refreshRes = await fetch(restRoot + "/offers/v1/refresh-nonce", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
        });
        if (refreshRes.ok) {
          const refreshData = await refreshRes.json();
          nonce = refreshData.nonce; // Update the global nonce variable

          // Retry delete with new nonce
          const retryRes = await fetch(restRoot + "/offers/v1/delete", {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
              "X-WP-Nonce": nonce,
            },
            body: JSON.stringify({ id }),
          });

          if (!retryRes.ok) {
            const retryErr = await retryRes.json();
            alert(
              "Error: " +
                (retryErr.message || "delete failed after nonce refresh")
            );
            return;
          }
          await fetchOffers();
          return;
        }
      }

      alert("Error: " + (err.message || "delete failed"));
      return;
    }
    await fetchOffers();
  }

  // when user saves via modal: load existing offers, modify/add, then save full array
  form.addEventListener("submit", async (ev) => {
    ev.preventDefault();
    try {
      const currentRes = await fetch(restRoot + "/offers/v1/list");
      const current = await currentRes.json();
      const payload = {
        id:
          idInput.value ||
          String(Date.now()) + Math.random().toString(36).substr(2, 5),
        image: imageInput.value.trim(),
        title: titleInput.value.trim(),
        description: descInput.value.trim(),
        voucher: voucherInput.value.trim(),
        buttonText: buttonTextInput.value.trim(),
        buttonLink: buttonLinkInput.value.trim(),
      };

      let newArr = current.filter(Boolean);
      const existingIndex = newArr.findIndex((it) => it.id === payload.id);
      if (existingIndex >= 0) {
        newArr[existingIndex] = payload;
      } else {
        newArr.push(payload);
      }

      await saveOffersArray(newArr);
      await fetchOffers();
      closeModal();
    } catch (e) {
      console.error(e);
      alert("Error saving offer: " + e.message);
    }
  });

  // Also handle the save button click
  document.getElementById("soa-save").addEventListener("click", (e) => {
    e.preventDefault();
    form.dispatchEvent(new Event("submit"));
  });

  // basic modal controls
  btnAdd.addEventListener("click", () => {
    console.log("Add button clicked");
    openModal("add");
  });
  modalClose.addEventListener("click", closeModal);
  document.getElementById("soa-cancel").addEventListener("click", (e) => {
    e.preventDefault();
    closeModal();
  });
  btnRefresh.addEventListener("click", fetchOffers);

  // Media uploader (WordPress)
  mediaBtn.addEventListener("click", (e) => {
    e.preventDefault();
    if (typeof wp === "undefined" || !wp.media) {
      alert("WP Media not available.");
      return;
    }
    // Create frame if not exists
    if (wpMediaFrame) {
      wpMediaFrame.open();
      return;
    }
    wpMediaFrame = wp.media({
      title: "Select or Upload an image",
      button: { text: "Use this image" },
      multiple: false,
    });

    wpMediaFrame.on("select", function () {
      const attachment = wpMediaFrame.state().get("selection").first().toJSON();
      if (attachment && attachment.url) {
        imageInput.value = attachment.url;

        const preview = document.querySelector("#soa-image-preview");
        if (preview) {
          preview.src = attachment.url;
          preview.style.display = "flex";
        }
      }
    });

    wpMediaFrame.open();
  });

  // click outside modal closes
  window.addEventListener("click", (e) => {
    if (e.target === modal) closeModal();
  });

  // initialize
  fetchOffers();
})();
