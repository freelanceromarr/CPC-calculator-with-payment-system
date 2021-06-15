<template>
   <div>
       <!-- <Menu/> -->
        <jet-authentication-card>
			<template #logo>
				<div class="grid grid-cols-3 md:grid-cols-6 gap-5 mx-10">
					<div class="rounded h-full flex justify-center items-center">
						<div
							class="rounded bg-white shadow-md h-32 w-48 p-6 flex flex-col justify-around"
						>
							<div>
								<p class="md:text-xl text-base text-gray-600">Avg. Revenue</p>
							</div>
							<div>
								<p class="md:text-2xl text-base text-gray-700 font-bold">
									$ <span>{{ averageRevenuePerClick }}</span>
								</p>
							</div>
						</div>
					</div>
					<div class="rounded h-full flex justify-center items-center">
						<div
							class="rounded bg-white shadow-md h-32 w-48 p-6 flex flex-col justify-around"
						>
							<div>
								<p class="md:text-xl text-base text-gray-600">Target CPC</p>
							</div>
							<div>
								<p class="md:text-2xl text-base text-gray-700 font-bold">
									$<span>{{ targetCPC }}</span>
								</p>
							</div>
						</div>
					</div>
					<div class="rounded h-full flex justify-center items-center">
						<div
							class="rounded bg-white shadow-md h-32 w-48 p-6 flex flex-col justify-around"
						>
							<div>
								<p class="md:text-xl text-base text-gray-600">Target ACOS</p>
							</div>
							<div>
								<p class="md:text-2xl text-base text-gray-700 font-bold">
									<span>{{ targetACOS }}</span
									>%
								</p>
							</div>
						</div>
					</div>
					<div class="rounded h-full flex justify-center items-center">
						<div
							class="rounded bg-white shadow-md h-32 w-48 p-6 flex flex-col justify-around"
						>
							<div>
								<p class="md:text-xl text-base text-gray-600">Spend</p>
							</div>
							<div>
								<p class="md:text-2xl text-base text-gray-700 font-bold">
									$<span>{{ Spend }}</span>
								</p>
							</div>
						</div>
					</div>
					<div class="rounded h-full flex justify-center items-center">
						<div
							class="rounded bg-white shadow-md h-32 w-48 p-6 flex flex-col justify-around"
						>
							<div>
								<p class="md:text-xl text-base text-gray-600">Conversions</p>
							</div>
							<div>
								<p class="md:text-2xl text-base text-gray-700 font-bold">
									$<span>{{ Conversions }}</span>
								</p>
							</div>
						</div>
					</div>
					<div class="rounded h-full flex justify-center items-center">
						<div
							class="rounded bg-white shadow-md h-32 w-48 p-6 flex flex-col justify-around"
						>
							<div>
								<p class="md:text-xl text-base text-gray-600">Revenue</p>
							</div>
							<div>
								<p class="md:text-2xl text-base text-gray-700 font-bold">
									$<span>{{ totalRevenue }}</span>
								</p>
							</div>
						</div>
					</div>
				</div>
			</template>

			<jet-validation-errors class="mb-4" />

			<form @submit.prevent="submit">
				<div>
					<jet-label for="conversion" value="Enter Conversion Rate (%)" />
					<jet-input
						id="conversion"
						type="number"
						class="mt-1 block w-full"
						v-model.number="form.conversionRate"
						required
						autofocus
						autocomplete="conversion"
						min="1"
						max="100"
					/>
				</div>

				<div class="mt-4">
					<jet-label for="Revenue" value="Enter Revenue Per Conversion" />
					<jet-input
						id="Revenue"
						type="number"
						class="mt-1 block w-full"
						v-model.number="form.revenue"
						required
					/>
				</div>

				<div class="mt-4">
					<jet-label for="ACOS" value="Target ACOS (%)" />
					<jet-input
						id="ACOS"
						type="number"
						class="mt-1 block w-full"
						v-model.number="form.acos"
						required
						autocomplete="ACOS"
					/>
				</div>
				<div class="mt-4">
					<jet-label for="Clicks" value="Total Clicks (default 1000 clicks)" />
					<jet-input
						id="Clicks"
						type="number"
						class="mt-1 block w-full"
						v-model.number="form.Clicks"
						required
						autocomplete="Clicks"
					/>
				</div>

				<div class="flex items-center justify-end mt-4">
					<a
						href="https://candealonline.com.au/contact-us/"
                        target = "_blank"
						class="underline text-sm text-gray-600 hover:text-gray-900"
					>
						Do you need support?
					</a>

					<jet-button
						class="ml-4"
						:class="{ 'opacity-25': form.processing }"
						:disabled="form.processing"
					>
						Calculate
					</jet-button>
				</div>
				<hr class="my-4" />
				<div class="flex items-center justify-between mt-4">
					<div class="text-sm text-gray-600 hover:text-gray-900">
						Average Revenue Per Click: <span>$</span>
						{{ averageRevenuePerClick }}
					</div>

					<div
						class="ml-4"
						:class="{ 'opacity-25': form.processing }"
						:disabled="form.processing"
					>
						Target CPC <span>$</span> {{ targetCPC }}
					</div>
				</div>
			</form>
		</jet-authentication-card>
   </div>

</template>

<script>
// import Menu from "@/Pages/menu";
import JetAuthenticationCard from "@/Jetstream/AuthenticationCard";
import JetAuthenticationCardLogo from "@/Jetstream/AuthenticationCardLogo";
import JetButton from "@/Jetstream/Button";
import JetInput from "@/Jetstream/Input";
import JetCheckbox from "@/Jetstream/Checkbox";
import JetLabel from "@/Jetstream/Label";
import JetValidationErrors from "@/Jetstream/ValidationErrors";

export default {
	components: {
		JetAuthenticationCard,
		JetAuthenticationCardLogo,
		JetButton,
		JetInput,
		JetCheckbox,
		JetLabel,
		JetValidationErrors,
		// Menu,
	},
	created() {
		console.log(this.form);
	},

	data() {
		return {
			form: {
				conversionRate: 0.0,
				revenue: 0.0,
				acos: 0.0,
				Clicks: 1000,
			},
			targetCPC: "0.00",
			averageRevenuePerClick: "0.00",
			Spend: "0.00",
			Conversions: "0.00",
			totalRevenue: "0.00",
			targetACOS: "0.00",
		};
	},

	methods: {
		submit() {
			// this.form.post(this.route('register'), {
			//     onFinish: () => this.form.reset('password', 'password_confirmation'),
			// })
			// this.averageRevenuePerClick = `${ parseFloat( this.conversionRate) * parseFloat(this.revenue)}`;
			// this.targetCPC = this.averageCost * this.acos;
			// console.log(this.form.conversionRate * this.form.revenue)
			this.averageRevenuePerClick = `${parseFloat(
				(this.form.conversionRate / 100) * this.form.revenue
			).toFixed(2)}`;
			this.targetCPC = `${parseFloat(
				(this.form.acos / 100) * this.averageRevenuePerClick
			).toFixed(2)}`;
			this.Spend = `${parseFloat(this.form.Clicks * this.targetCPC).toFixed(
				2
			)}`;
			this.Conversions = `${parseFloat(
				this.form.Clicks * (this.form.conversionRate / 100)
			).toFixed(2)}`;
			this.totalRevenue = `${parseFloat(
				this.Conversions * this.form.revenue
			).toFixed(2)}`;
			this.targetACOS = `${parseFloat(
				(this.Spend / this.totalRevenue) * 100
			).toFixed(2)}`;
		},
	},
};
</script>
