
<!-- Operation icons - create -->
<div class="operation" onclick="toggleOperationDetails('create')">
                <i class="fa fa-plus" style="color: rgb(131, 26, 26); margin-right: 5px;"></i><a href="#" style="text-decoration: none; color: black;">Create</a>
            </div>
            <div class="operation-details" id="create-details">
                <form id="create-form" onsubmit="handleSubmit('create')">
                
                    <label for="fname">First Name<span class="required"></span></label>
                        <input type="text" id="fname" name="firstname" placeholder="Your first name.." required>

                    <label for="lname">Last Name<span class="required"></span></label>
                        <input type="text" id="lname" name="lastname" placeholder="Your last name.." required>

                    <label for="address">Address<span class="required"></span></label>
                        <input type="text" id="address" name="address" placeholder="Your address.." required>

                    <label for="nic">NIC Number<span class="required"></span></label>
                        <input type="text" id="nic" name="nic" placeholder="Your NIC number.." required>
            
                    <label for="contact">Contact Number<span class="required"></span></label>
                        <input type="text" id="contact" name="contact" placeholder="Your contact number.." required>
            
                    <label for="email">E-mail<span class="required"></span></label>
                        <input type="text" id="email" name="email" placeholder="Your e-mail.." required>
            
                    <label for="blood">Blood Group<span class="required"></span></label>
                        <select id="blood" name="blood">
                            <option value="A+">A+</option>
                            <option value="A-">A-</option>
                            <option value="B+">B+</option>
                            <option value="B-">B-</option>
                            <option value="O+">O+</option>
                            <option value="O-">O-</option>
                            <option value="AB+">AB+</option>
                            <option value="AB-">AB-</option>
                        </select>

                    <label for="password">Password<span class="required"></span></label>
                        <input type="password" id="password" name="password" placeholder="Your password.." required>

                    <input type="submit" value="Create Account">
            
                </form>    
                <!-- Result placeholder for create operation -->
                <div id="create-result"></div> 
            </div>