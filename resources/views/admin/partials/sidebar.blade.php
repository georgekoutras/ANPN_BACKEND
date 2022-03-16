<div class="sidebar">
	<div class="sidebar-inner">
		<!-- ### $Sidebar Header ### -->
		<div class="sidebar-logo">
			<div class="peers ai-c fxw-nw">
				<div class="peer peer-greed">
                    @if(auth()->user()->role == 'patient')
                        <a class='sidebar-link td-n' href="{{ route('daily_reports.patients.reports', $patientId) }}">
                    @else
                        <a class='sidebar-link td-n' href="/">
                    @endif
						<div class="peers ai-c fxw-nw">
							<div class="peer">
								<div class="logo">
									<img src="/images/logo_rec.png" alt="">
								</div>
							</div>
<!--							<div class="peer peer-greed">
								<h5 class="lh-1 mB-0 logo-text">Anapneo</h5>
							</div>-->
						</div>
					</a>
				</div>
				<div class="peer">
					<div class="mobile-toggle sidebar-toggle">
						<a href="" class="td-n">
							<i class="ti-arrow-circle-left"></i>
						</a>
					</div>
				</div>
			</div>
		</div>

		<!-- ### $Sidebar Menu ### -->
		<ul class="sidebar-menu scrollable pos-r">
			@include('admin.partials.menu')
		</ul>
	</div>
</div>
