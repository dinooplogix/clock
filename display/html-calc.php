<div class="calc container">
    <div class="col-md-9">
        <div class="row">
            <h4>Virtual Server</h4><br />
            <div class="col-md-1">Core:</div>
            <div class="col-md-11"><div class="core"></div></div>

            <div class="col-md-1">Ram:</div>
            <div class="col-md-11"><div class="ram"></div></div>

            <div class="col-md-1">Storage:</div>
            <div class="col-md-11"><div class="storage"></div></div>
        </div>
    </div>
    <div class="col-md-3">
        <h4>Private Node</h4>
        <p>Turn off to change the node from private to public</p>
        <input type="checkbox" name="server-node" checked>
        <h4>Total</h4>
        <button type="button" class="btn btn-danger">$<span id="amount-hour"></span>/Hr</button>
        <button type="button" class="btn btn-danger">$<span id="amount-month"></span>/Mo</button>

    </div>
</div>

<script>
    jQuery(function ($) {
        
        function roundit(num) {
            return Math.round(num * 1000) / 1000;
        }
        var $server_node = $("[name='server-node']");

        $server_node.bootstrapSwitch();
        var bill = {
            basic_core_per_hour: 0.0035,
            basic_ram_per_hour: 0.0045,
            basic_storage_per_hour: 0.0055,
            basic_core_per_month: 0.0320,
            basic_ram_per_month: 0.250,
            basic_storage_per_month: 0.820,
            nod_percentage: function () {
                if ($server_node.is(":checked")) {
                    //private node
                    return 0;
                } else {
                    // public node 
                    return 40;
                }
            },
            core: [1, 2, 4, 8, 12, 16],
            ram: [1, 2, 4, 6, 8, 12, 32, 48, 64],
            storage: [25, 50, 100],
            chosen_core: null,
            chosen_ram: null,
            chosen_storage: null,
            set_default: function () {
                this.chosen_core = this.core[0];
                this.chosen_ram = this.ram[0];
                this.chosen_storage = this.storage[0];
            },
            get_current_amount_hour: function () {
                var tot = this.chosen_core * this.basic_core_per_hour +
                        this.chosen_ram * this.basic_ram_per_hour +
                        this.chosen_storage * this.basic_storage_per_hour;

                return roundit(tot + tot * this.nod_percentage() / 100);
            },
            get_current_amount_month: function () {
                var tot = this.chosen_core * this.basic_core_per_month +
                        this.chosen_ram * this.basic_ram_per_month +
                        this.chosen_storage * this.basic_storage_per_month;
                return roundit(tot + tot * this.nod_percentage() / 100);
            },
            display_amount: function () {



                if (this.chosen_core == null) {
                    this.set_default();
                }
                $("#amount-hour").text(this.get_current_amount_hour());
                $("#amount-month").text(this.get_current_amount_month());
            },
        }



        bill.display_amount();

        $server_node.on('switchChange.bootstrapSwitch', function (event, state) {
            bill.display_amount();
        });



        $(".core").slider({max: bill.core.length - 1}).slider("pips", {
            rest: "label",
            step: 1,
            labels: bill.core,
        }).on("slidechange", function (e, ui) {
            bill.chosen_core = bill.core[ui.value];
            bill.display_amount();
        });
        
        $(".ram").slider({max: bill.ram.length - 1}).slider("pips", {
            rest: "label",
            step: 1,
            labels: bill.ram,
        }).on("slidechange", function (e, ui) {
            bill.chosen_core = bill.ram[ui.value];
            bill.display_amount();
        });
        
        $(".storage").slider({max: bill.storage.length - 1}).slider("pips", {
            rest: "label",
            step: 1,
            labels: bill.storage,
        }).on("slidechange", function (e, ui) {
            bill.chosen_core = bill.storage[ui.value];
            bill.display_amount();
        });


        
    });

</script>