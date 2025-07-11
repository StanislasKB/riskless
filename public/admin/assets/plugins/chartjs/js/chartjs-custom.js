$(function () {
	"use strict";
	// chart 1
const canvas = document.getElementById("chart1");
const ctx = canvas.getContext("2d");
const raw = canvas.getAttribute("data-graphe");
const chartData = JSON.parse(raw);

new Chart(ctx, {
	type: 'bar', // base type, mais chaque dataset peut être différent
	data: {
		labels: chartData.labels,
		datasets: chartData.datasets
	},
	options: {
		maintainAspectRatio: false,
		plugins: {
			legend: {
				display: true,
				labels: {
					filter: function (legendItem, data) {
						// Affiche seulement une légende "Valeur mesurée" (barres) et "Seuil d'alerte" (ligne)
						const label = legendItem.text;
						return label === "Valeur mesurée" || label === "Seuil d'alerte";
					}
				}
			},
			title: {
				display: true,
				text: "Évolution mensuelle vs Seuil d'alerte"
			}
		},
		scales: {
			x: {
				title: {
					display: true,
					text: "Mois"
				}
			},
			y: {
				beginAtZero: true,
				title: {
					display: true,
					text: "Valeur"
				}
			}
		}
	}
});
	// chart 2
	var ctx = document.getElementById("chart2").getContext('2d');
	var myChart = new Chart(ctx, {
		type: 'bar',
		data: {
			labels: ['Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa', 'Su'],
			datasets: [{
				label: 'Google',
				data: [13, 20, 4, 18, 29, 25, 8],
				barPercentage: .5,
				backgroundColor: "#673ab7"
			}, {
				label: 'Facebook',
				data: [31, 30, 6, 6, 21, 4, 11],
				barPercentage: .5,
				backgroundColor: "#bf9bff"
			}]
		},
		options: {
			maintainAspectRatio: false,
			legend: {
				display: true,
				labels: {
					fontColor: '#585757',
					boxWidth: 40
				}
			},
			tooltips: {
				enabled: true
			},
			scales: {
				xAxes: [{
					ticks: {
						beginAtZero: true,
						fontColor: '#585757'
					},
					gridLines: {
						display: true,
						color: "rgba(0, 0, 0, 0.07)"
					},
				}],
				yAxes: [{
					ticks: {
						beginAtZero: true,
						fontColor: '#585757'
					},
					gridLines: {
						display: true,
						color: "rgba(0, 0, 0, 0.07)"
					},
				}]
			}
		}
	});
	// chart 3
	new Chart(document.getElementById("chart3"), {
		type: 'pie',
		data: {
			labels: ["Africa", "Asia", "Europe", "Latin America", "North America"],
			datasets: [{
				label: "Population (millions)",
				backgroundColor: ["#673ab7", "#32ab13", "#f02769", "#ffc107", "#198fed"],
				data: [2478, 5267, 734, 784, 433]
			}]
		},
		options: {
			maintainAspectRatio: false,
			title: {
				display: true,
				text: 'Predicted world population (millions) in 2050'
			}
		}
	});
	// chart 4
	new Chart(document.getElementById("chart4"), {
		type: 'radar',
		data: {
			labels: ["Africa", "Asia", "Europe", "Latin America", "North America"],
			datasets: [{
				label: "1950",
				fill: true,
				backgroundColor: "rgba(179,181,198,0.2)",
				borderColor: "rgba(179,181,198,1)",
				pointBorderColor: "#fff",
				pointBackgroundColor: "rgba(179,181,198,1)",
				data: [8.77, 55.61, 21.69, 6.62, 6.82]
			}, {
				label: "2050",
				fill: true,
				backgroundColor: "rgba(255,99,132,0.2)",
				borderColor: "rgba(255,99,132,1)",
				pointBorderColor: "#fff",
				pointBackgroundColor: "rgba(255,99,132,1)",
				pointBorderColor: "#fff",
				data: [25.48, 54.16, 7.61, 8.06, 4.45]
			}]
		},
		options: {
			maintainAspectRatio: false,
			title: {
				display: true,
				text: 'Distribution in % of world population'
			}
		}
	});
	// chart 5
	new Chart(document.getElementById("chart5"), {
		type: 'polarArea',
		data: {
			labels: ["Africa", "Asia", "Europe", "Latin America", "North America"],
			datasets: [{
				label: "Population (millions)",
				backgroundColor: ["#673ab7", "#32ab13", "#f02769", "#ffc107", "#198fed"],
				data: [2478, 5267, 734, 784, 433]
			}]
		},
		options: {
			maintainAspectRatio: false,
			title: {
				display: true,
				text: 'Predicted world population (millions) in 2050'
			}
		}
	});
	// chart 6
	new Chart(document.getElementById("chart6"), {
		type: 'doughnut',
		data: {
			labels: ["Africa", "Asia", "Europe", "Latin America", "North America"],
			datasets: [{
				label: "Population (millions)",
				backgroundColor: ["#673ab7", "#32ab13", "#f02769", "#ffc107", "#198fed"],
				data: [2478, 5267, 734, 784, 433]
			}]
		},
		options: {
			maintainAspectRatio: false,
			title: {
				display: true,
				text: 'Predicted world population (millions) in 2050'
			}
		}
	});
	// chart 7
	new Chart(document.getElementById("chart7"), {
		type: 'horizontalBar',
		data: {
			labels: ["Africa", "Asia", "Europe", "Latin America", "North America"],
			datasets: [{
				label: "Population (millions)",
				backgroundColor: ["#673ab7", "#32ab13", "#f02769", "#ffc107", "#198fed"],
				data: [2478, 5267, 734, 784, 433]
			}]
		},
		options: {
			maintainAspectRatio: false,
			legend: {
				display: false
			},
			title: {
				display: true,
				text: 'Predicted world population (millions) in 2050'
			}
		}
	});
	// chart 8
	new Chart(document.getElementById("chart8"), {
		type: 'bar',
		data: {
			labels: ["1900", "1950", "1999", "2050"],
			datasets: [{
				label: "Africa",
				backgroundColor: "#673ab7",
				data: [133, 221, 783, 2478]
			}, {
				label: "Europe",
				backgroundColor: "#f02769",
				data: [408, 547, 675, 734]
			}]
		},
		options: {
			maintainAspectRatio: false,
			title: {
				display: true,
				text: 'Population growth (millions)'
			}
		}
	});
	// chart 9
	new Chart(document.getElementById("chart9"), {
		type: 'bar',
		data: {
			labels: ["1900", "1950", "1999", "2050"],
			datasets: [{
				label: "Europe",
				type: "line",
				borderColor: "#673ab7",
				data: [408, 547, 675, 734],
				fill: false
			}, {
				label: "Africa",
				type: "line",
				borderColor: "#f02769",
				data: [133, 221, 783, 2478],
				fill: false
			}, {
				label: "Europe",
				type: "bar",
				backgroundColor: "rgba(0,0,0,0.2)",
				data: [408, 547, 675, 734],
			}, {
				label: "Africa",
				type: "bar",
				backgroundColor: "rgba(0,0,0,0.2)",
				backgroundColorHover: "#3e95cd",
				data: [133, 221, 783, 2478]
			}]
		},
		options: {
			maintainAspectRatio: false,
			title: {
				display: true,
				text: 'Population growth (millions): Europe & Africa'
			},
			legend: {
				display: false
			}
		}
	});
	// chart 10
	new Chart(document.getElementById("chart10"), {
		type: 'bubble',
		data: {
			labels: "Africa",
			datasets: [{
				label: ["China"],
				backgroundColor: "#673ab7",
				borderColor: "#673ab7",
				data: [{
					x: 21269017,
					y: 5.245,
					r: 15
				}]
			}, {
				label: ["Denmark"],
				backgroundColor: "#198fed",
				borderColor: "#198fed",
				data: [{
					x: 258702,
					y: 7.526,
					r: 10
				}]
			}, {
				label: ["Germany"],
				backgroundColor: "#ffc107",
				borderColor: "#ffc107",
				data: [{
					x: 3979083,
					y: 6.994,
					r: 15
				}]
			}, {
				label: ["Japan"],
				backgroundColor: "#f02769",
				borderColor: "#f02769",
				data: [{
					x: 4931877,
					y: 5.921,
					r: 15
				}]
			}]
		},
		options: {
			maintainAspectRatio: false,
			title: {
				display: true,
				text: 'Predicted world population (millions) in 2050'
			},
			scales: {
				yAxes: [{
					scaleLabel: {
						display: true,
						labelString: "Happiness"
					}
				}],
				xAxes: [{
					scaleLabel: {
						display: true,
						labelString: "GDP (PPP)"
					}
				}]
			}
		}
	});
});