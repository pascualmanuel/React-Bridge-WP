<?php
if ( ! defined( 'ABSPATH' ) ) exit;
$offers = soa_read_offers();
?>


<style> .soa-wrap h1 { margin-bottom: 8px; } .soa-actions { margin-bottom: 16px; display: flex; gap: 8px; align-items: center; } .soa-list { display: flex; flex-direction: column; gap: 12px; } .soa-card { display: flex; gap: 12px; padding: 12px; border: 1px solid #e5e5e5; background: #fff; border-radius: 6px; align-items: center; } .soa-card-left .soa-thumb { width: 120px; height: 70px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd; } .soa-thumb.empty { display: flex; align-items: center; justify-content: center; width: 120px; height: 70px; background: #f5f5f5; color: #777; border-radius: 4px; } .soa-card-body { flex: 1; } .soa-card-body h3 { margin: 0 0 6px 0; font-size: 16px; } .soa-card-body .soa-desc { margin: 0 0 6px 0; color: #555; } .soa-card-body .soa-voucher { margin: 0 0 6px 0; font-weight: 600; color: #333; } .soa-card-actions { display: flex; flex-direction: column; gap: 6px; } .soa-empty { padding: 20px; color: #666; background: #fafafa; border: 1px dashed #eee; border-radius: 6px; } .soa-modal { display: none; position: fixed; inset: 0; background: rgba(0, 0, 0, 0.4); z-index: 99999; align-items: center; justify-content: center; } .soa-modal-content { background: #fff; width: 820px; max-width: 95%; padding: 20px; border-radius: 8px; position: relative; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12); } .soa-modal-close { position: absolute; right: 10px; top: 8px; border: none; background: none; font-size: 22px; cursor: pointer; } .form-table th { width: 180px; vertical-align: top; padding-top: 10px; } .form-table td { padding-top: 8px; } @media (max-width: 600px) { .soa-card { flex-direction: column; align-items: flex-start; } .soa-card-left .soa-thumb { width: 100%; height: 160px; } } /* Imagen de preview más chica */ .offer-item img { max-width: 500px; height: auto; border-radius: 8px; display: block; margin-bottom: 10px; } /* Formulario (editor) arriba del listado */ #offers-editor { margin-bottom: 40px; background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1); } /* Contenedor de las cards */ #offers-list { display: grid; gap: 20px; } /* Tarjetas individuales */ .offer-item { background: #fff; border-radius: 10px; padding: 15px; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); } /* Botones */ .offer-item button { margin-right: 10px; background-color: #0073aa; color: #fff; border: none; padding: 6px 10px; border-radius: 4px; cursor: pointer; } .offer-item button:hover { background-color: #005177; } /* Limitar tamaño de la miniatura en el listado */ .soa-thumb { max-width: 500px !important; height: auto !important; object-fit: contain; border-radius: 8px; display: block; } </style>



<div class="wrap soa-wrap">
    <h1>Simple Offers API</h1>
    <p class="description">Admin minimal para agregar/editar/eliminar las ofertas que entrega el endpoint REST.</p>

    <div class="soa-actions">
        <button id="soa-add-offer" class="button button-primary">+ Add Offer</button>
        <button id="soa-refresh" class="button">Refresh</button>
    </div>

    <!-- Modal -->
<div id="soa-modal" style="margin-top: 30px;" class="soa-modal" aria-hidden="true">
    <div class="soa-modal-content">
        <button class="soa-modal-close" id="soa-modal-close">×</button>
        <h2 id="soa-modal-title">Add Offer</h2>

        <form id="soa-form">
            <input type="hidden" name="id" id="soa-id" />
            <table class="form-table">
                <tbody>
                    <tr>
                        <th><label for="soa-image">Image URL</label></th>
                        <td>
                            <input type="url" id="soa-image" name="image" class="regular-text" placeholder="https://example.com/img.jpg" />
                            <p class="description">También podés pegar una URL desde la Biblioteca de Medios.</p>
                            <p><button type="button" class="button" id="soa-media-btn">Choose from Media</button></p>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="soa-title">Title</label></th>
                        <td><input id="soa-title" name="title" class="regular-text" /></td>
                    </tr>
                    <tr>
                        <th><label for="soa-desc">Description</label></th>
                        <td><textarea id="soa-desc" name="description" rows="3" class="large-text"></textarea></td>
                    </tr>
                    <tr>
                        <th><label for="soa-voucher">Voucher</label></th>
                        <td><input id="soa-voucher" name="voucher" class="regular-text" /></td>
                    </tr>
                    <tr>
                        <th><label for="soa-button-text">Button Text</label></th>
                        <td><input id="soa-button-text" name="buttonText" class="regular-text" /></td>
                    </tr>
                    <tr>
                        <th><label for="soa-button-link">Button Link</label></th>
                        <td><input id="soa-button-link" name="buttonLink" class="regular-text" /></td>
                    </tr>
                </tbody>
            </table>

            <p class="submit">
                <button id="soa-save" class="button button-primary">Save Offer</button>
                <button id="soa-cancel" type="button" class="button">Cancel</button>
            </p>
        </form>
    </div>
</div>


    <div id="soa-list" style="margin-top: 30px;" class="soa-list">
        <?php if ( empty( $offers ) ) : ?>
            <div class="soa-empty">No offers yet. Click <strong>+ Add Offer</strong> to create one.</div>
        <?php else: ?>
            <?php foreach ( $offers as $offer ): ?>
                <div class="soa-card" data-id="<?php echo esc_attr( $offer['id'] ); ?>">
                    <div class="soa-card-left">
                        <?php if ( ! empty( $offer['image'] ) ): ?>
                            <img src="<?php echo esc_url( $offer['image'] ); ?>" alt="" class="soa-thumb" />
                        <?php else: ?>
                            <div class="soa-thumb empty">No image</div>
                        <?php endif; ?>
                    </div>
                    <div class="soa-card-body">
                        <h3><?php echo esc_html( $offer['title'] ); ?></h3>
                        <p class="soa-desc"><?php echo esc_html( $offer['description'] ); ?></p>
                        <p class="soa-voucher"><?php echo esc_html( $offer['voucher'] ); ?></p>
                        <p class="soa-cta"><a href="<?php echo esc_url( $offer['buttonLink'] ); ?>"><?php echo esc_html( $offer['buttonText'] ); ?></a></p>
                    </div>
                    <div class="soa-card-actions">
                        <button class="button soa-edit">✏️ Edit</button>
                        <button class="button soa-delete">🗑️ Delete</button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

