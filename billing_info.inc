<?php
?>
    <div id="billingInfoLightBox" class="lightBox">
        <div class="title" id="payment">
            <p>Add a Secure Payment</p>
        </div>
        <div class="title" id="billing">
            <p>Billing Address</p>
        </div>
        <form id='billingInfo_form' action='' method='post'>
        <div class="paymentInfo">
            <p class="PayPal">PayPal</p>
            <img src="./images/icons/PayPal.png" alt="PayPal picture" height="42" width="200">
            <p class="CreditCard">Credit Card</p>
            <p>First Name*</p>
            <input type="text" class="firstName" value=""><br>
            <p>Last Name*</p>
            <input type="text" class="lastName" value=""><br>
            <select class="selectState">
                <option value="">Select State</option>
                <option value="AL">AL</option>
                <option value="AK">AK</option>
                <option value="AZ">AZ</option>
                <option value="AR">AR</option>
                <option value="CA">CA</option>
                <option value="CO">CO</option>
                <option value="CT">CT</option>
                <option value="DE">DE</option>
                <option value="DC">DC</option>
                <option value="FL">FL</option>
                <option value="GA">GA</option>
                <option value="HI">HI</option>
                <option value="ID">ID</option>
                <option value="IL">IL</option>
                <option value="IN">IN</option>
                <option value="IA">IA</option>
                <option value="KS">KS</option>
                <option value="KY">KY</option>
                <option value="LA">LA</option>
                <option value="ME">ME</option>
                <option value="MD">MD</option>
                <option value="MA">MA</option>
                <option value="MI">MI</option>
                <option value="MN">MN</option>
                <option value="MS">MS</option>
                <option value="MO">MO</option>
                <option value="MT">MT</option>
                <option value="NE">NE</option>
                <option value="NV">NV</option>
                <option value="NH">NH</option>
                <option value="NJ">NJ</option>
                <option value="NM">NM</option>
                <option value="NY">NY</option>
                <option value="NC">NC</option>
                <option value="ND">ND</option>
                <option value="OH">OH</option>
                <option value="OK">OK</option>
                <option value="OR">OR</option>
                <option value="PA">PA</option>
                <option value="RI">RI</option>
                <option value="SC">SC</option>
                <option value="SD">SD</option>
                <option value="TN">TN</option>
                <option value="TX">TX</option>
                <option value="UT">UT</option>
                <option value="VT">VT</option>
                <option value="VA">VA</option>
                <option value="WA">WA</option>
                <option value="WV">WV</option>
                <option value="WI">WI</option>
                <option value="WY">WY</option>   
            </select><br>
            <p>Card Number*</p>
            <input type="text" class="lastName" value=""><br>
            <p class="citytext">Expire Date*</p> <p class="ziptext">CVC*</p>
            <input type="text" class="city" value="">
            <input type="text" class="zip" value="">
            <div class="save_payment"><input type="checkbox" name="save_payment" >Save payment info</div><br>
            <div class="save_default_payment"><input type="checkbox" name="save_default_payment" >Save as my default payment method</div><br>
        </div>
        <div class="billingInfo">
            <p>First Name*</p>
            <input type="text" class="firstName" name="firstName" value=""><br>
            <p>Last Name*</p>
            <input type="text" class="lastName" name="lastName" value=""><br>
            <p>Address Line 1*</p>
            <input type="text" class="add1" name="add1" value=""><br>
            <p>Address Line 2</p>
            <input type="text" class="add2" value=""><br>
            <p class="citytext">City*</p> <p class="ziptext">Zip/Postal Code*</p>
            <input type="text" class="city" name="city" value="">
            <input type="text" class="zip" name="zip" value="">
            <select class="selectState" name="state">
                <option value="">Select State</option>
                <option value="AL">AL</option>
                <option value="AK">AK</option>
                <option value="AZ">AZ</option>
                <option value="AR">AR</option>
                <option value="CA">CA</option>
                <option value="CO">CO</option>
                <option value="CT">CT</option>
                <option value="DE">DE</option>
                <option value="DC">DC</option>
                <option value="FL">FL</option>
                <option value="GA">GA</option>
                <option value="HI">HI</option>
                <option value="ID">ID</option>
                <option value="IL">IL</option>
                <option value="IN">IN</option>
                <option value="IA">IA</option>
                <option value="KS">KS</option>
                <option value="KY">KY</option>
                <option value="LA">LA</option>
                <option value="ME">ME</option>
                <option value="MD">MD</option>
                <option value="MA">MA</option>
                <option value="MI">MI</option>
                <option value="MN">MN</option>
                <option value="MS">MS</option>
                <option value="MO">MO</option>
                <option value="MT">MT</option>
                <option value="NE">NE</option>
                <option value="NV">NV</option>
                <option value="NH">NH</option>
                <option value="NJ">NJ</option>
                <option value="NM">NM</option>
                <option value="NY">NY</option>
                <option value="NC">NC</option>
                <option value="ND">ND</option>
                <option value="OH">OH</option>
                <option value="OK">OK</option>
                <option value="OR">OR</option>
                <option value="PA">PA</option>
                <option value="RI">RI</option>
                <option value="SC">SC</option>
                <option value="SD">SD</option>
                <option value="TN">TN</option>
                <option value="TX">TX</option>
                <option value="UT">UT</option>
                <option value="VT">VT</option>
                <option value="VA">VA</option>
                <option value="WA">WA</option>
                <option value="WV">WV</option>
                <option value="WI">WI</option>
                <option value="WY">WY</option>            
            </select><br>
            <p class="phone" >Phone*</p>
            <input type="text" class="phone" value="">
            <div class="save_payment"><input type="checkbox" name="save_payment" >Same Shipping Address</div><br>
            <input type='submit' class="save" value='Save'>
        </div>
        <div class="bottom">
            
            <p>*Required fields</p>
        </div>
        </form>
    </div>
<?php
?>