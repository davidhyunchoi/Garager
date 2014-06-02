<?php
// itemDescription.inc template file
?>
<form class="description" id="addImage1">
<div class="itemDescriptionLightBox lightBox">
    <div class="left">
        <img id="topPic1" src="">
        <div class="bottomPics">
            <img id="firstBottomPic1" src="">
            <img id="secondBottomPic1" src="">
            <img id="thirdBottomPic1" src="">
            <img id="fourthBottomPic1" src="">
            <img src="">
        </div>
    </div>
    <div class="middle">
        <div class="itemDescription">
            <input type="text" id="itemtitle" class="enterTitle" value="Enter Title">
            <input type="text" id="itemprice" class="enterPrice" value="Enter Price">
            <textarea class="itemDes" rows='4' cols='45' id="itemdescription">Description</textarea>
            <select id="itemcategory" class="selectCategories">
                <option value="">Select Categories</option>
                <option value="Photography">Photography </option>
                <option value="Sculpture">Sculpture   </option>
                <option value="Crafts">Crafts      </option>
                <option value="Collectible">Collectible </option>
                <option value="Art Books">Art Books   </option>
                <option value="Cook Books">Cook Books  </option>
                <option value="Fiction">Fiction     </option>
                <option value="Non-Fiction">Non-Fiction </option>
                <option value="Collectible">Collectible </option>
                <option value="Baby & Child">Baby & Child</option>
                <option value="Mens">Mens        </option>
                <option value="Women">Women       </option>
                <option value="Accessories">Accessories </option>
                <option value="Vintage">Vintage     </option>
                <option value="Watches">Watches              </option>
                <option value="Rings">Rings                </option>
                <option value="Earrings">Earrings             </option>
                <option value="Bracelets & Necklaces">Bracelets & Necklaces</option>
                <option value="Brooches & Pins">Brooches & Pins      </option>
                <option value="Mens">Mens                 </option>
                <option value="Vintage">Vintage              </option>
                <option value="Tables">Tables         </option>
                <option value="Chairs">Chairs         </option>
                <option value="Couches">Couches        </option>
                <option value="Desks">Desks          </option>
                <option value="Bookcases">Bookcases      </option>
                <option value="Beds">Beds           </option>
                <option value="Trunks">Trunks         </option>
                <option value="Sideboards">Sideboards     </option>
                <option value="Armories">Armories       </option>
                <option value="Lighting">Lighting       </option>
                <option value="Rugs & Flooring">Rugs & Flooring</option>
                <option value="Vintage">Vintage        </option>
                <option value="Kitchen">Kitchen     </option>
                <option value="Bath">Bath        </option>
                <option value="Dining & Bar">Dining & Bar</option>
                <option value="Decor">Decor       </option>
                <option value="Outdoor">Outdoor     </option>
                <option value="Garden">Garden      </option>
                <option value="Pets">Pets        </option>
                <option value="Tools">Tools       </option>
                <option value="Vintage">Vintage     </option>
                <option value="Sheet & Books">Sheet & Books</option>
                <option value="Instruments">Instruments  </option>
                <option value="Accessories">Accessories  </option>
                <option value="Equipment">Equipment  </option>
                <option value="Wearables">Wearables  </option>
                <option value="Accessories">Accessories</option>
                <option value="Computer">Computer      </option>
                <option value="Games">Games         </option>
                <option value="Mobile">Mobile        </option>
                <option value="Photo & Video">Photo & Video </option>
                <option value="Stereo & Radio">Stereo & Radio</option>
                <option value="TV">TV            </option>
                <option value="Vintage">Vintage       </option>
                <option value="Arts & Crafts">Arts & Crafts</option>
                <option value="Games">Games        </option>
                <option value="Dolls">Dolls        </option>
                <option value="Electronics">Electronics  </option>
                <option value="Riding Toys">Riding Toys  </option>
                <option value="Other">Other        </option>
                <option value="Vehicles">Vehicles     </option>
                <option value="Bikes">Bikes        </option>
                <option value="Motorbikes">Motorbikes   </option>
                <option value="Non-motorized">Non-motorized</option>
                <option value="Skates">Skates       </option>
            </select>
            <input type="text" id="itemtags" class="itemTags" value="Enter custome tags, seperated by commas">
            <select size="1" id="itemshippingtype" class="shipping">
                <option value="volvo">Shipping(select all that apply)</option>
                <option value="garagersale">Garager sale</option>
                <option value="local">Local Pickup</option>
                <option value="mail">Mail Delivery</option>
            </select>
            <input type="text" id="itemshippingcost" class="shippingCost" value="Shipping cost">
        </div>
        <div class="bottom">
            <input id="savelightbox" type="button" class="saveItem" value="SAVE">
        </div>
    </div>
</div>
</form>
<div class="lightBoxBackground">
</div>
<?php
// End itemDescription.inc template file
?>
